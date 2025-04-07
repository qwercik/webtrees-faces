<?php

namespace UksusoFF\WebtreesModules\Faces\Http\Controllers;

use Exception;
use Fisharebest\Webtrees\Age;
use Fisharebest\Webtrees\Date;
use Fisharebest\Webtrees\Fact;
use Fisharebest\Webtrees\Http\Exceptions\HttpNotFoundException;
use Fisharebest\Webtrees\Http\RequestHandlers\LinkMediaToRecordAction;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Media;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Services\LinkedRecordService;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Webtrees;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use UksusoFF\WebtreesModules\Faces\Helpers\AppHelper;
use UksusoFF\WebtreesModules\Faces\Helpers\ExifHelper;
use UksusoFF\WebtreesModules\Faces\Modules\FacesModule;
use UksusoFF\WebtreesModules\Faces\Wrappers\FactWrapper;

class DataController implements RequestHandlerInterface
{
    public const ROUTE_PREFIX = 'faces-data';

    protected FacesModule $module;

    protected LinkedRecordService $links;

    public function __construct(FacesModule $module)
    {
        $this->module = $module;

        $this->links = AppHelper::get(LinkedRecordService::class);
    }

    public function handle(Request $request): Response
    {
        try {
            $media = $this->getMedia($request);
            $fact = $this->getFact($request);

            switch ($request->getAttribute('action')) {
                case 'index':
                    return $this->index($request, $media, $fact);
                case 'attach':
                    return $this->attach($request, $media, $fact);
                case 'detach':
                    return $this->detach($request, $media, $fact);
                default:
                    throw new HttpNotFoundException();
            }
        } catch (Exception $e) {
            return response([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function index(Request $request, Media $media, string $fact): Response
    {
        if (!$media->canShow()) {
            throw new HttpNotFoundException();
        }

        $areas = $this->getMediaMap($media, $fact);
        $people = $this->getPeopleOnPhoto($media->tree(), $areas);

        $estimatedDateRangeStart = $this->estimateDateRangeStart($people);
        $estimatedDateRangeEnd = $this->estimateDateRangeEnd($people);

        return response([
            'success' => true,
            'url' => $media->url(),
            'note' => $media->getNote(),
            'title' => $this->getMediaTitle($media, $fact),
            'meta' => $this->module->settingEnabled(FacesModule::SETTING_META_NAME)
                ? $this->getMediaMeta($media)
                : [],
            'map' => $this->getMediaMapForTree($media, $areas, $people),
            'edit' => $media->canEdit(),
            'estimated_date_range' => $this->formatDateRange($estimatedDateRangeStart, $estimatedDateRangeEnd),
        ]);
    }

    private function formatDateRange(?Date $estimatedDateRangeStart, ?Date $estimatedDateRangeEnd): string
    {
        if ($estimatedDateRangeStart !== null && $estimatedDateRangeEnd !== null) {
            return I18N::translate('Estimated date range', $estimatedDateRangeStart->display(), $estimatedDateRangeEnd->display());
        }

        if ($estimatedDateRangeStart !== null) {
            return I18N::translate('Date after', $estimatedDateRangeStart->display());
        }

        if ($estimatedDateRangeEnd !== null) {
            return I18N::translate('Date before', $estimatedDateRangeEnd->display());
        }

        return '';
    }

    private function estimateDateRangeStart(array $people): ?Date
    {
        $birthDates = array_filter(array_map(fn($person) => $person->getBirthDate() , $people), fn($date) => $date->isOk());
        if (empty($birthDates)) {
            return null;
        }

        usort($birthDates, [Date::class, 'compare']);
        return end($birthDates);
    }

    private function estimateDateRangeEnd(array $people): ?Date
    {
        $deathDates = array_filter(array_map(fn($person) => $person->getDeathDate(), $people), fn($date) => $date->isOk());
        if (empty($deathDates)) {
            return null;
        }

        usort($deathDates, [Date::class, 'compare']);
        return head($deathDates);
    }

    private function attach(Request $request, Media $media, string $fact): Response
    {
        if (!$media->canEdit()) {
            throw new HttpNotFoundException();
        }

        $pid = $request->getParsedBody()['pid'] ?? null;
        $coords = $request->getParsedBody()['coords'] ?? null;

        if ($pid === null || $coords === null) {
            throw new HttpNotFoundException();
        }

        $map = $this->getMediaMap($media, $fact);

        $map[] = (object) [
            'pid' => $pid,
            'coords' => $coords,
        ];

        $this->setMediaMap($media, $fact, $map);

        $linked = $this->links->linkedIndividuals($media, 'OBJE')->first(function(Individual $individual) use ($pid) {
            return $individual->xref() === $pid;
        });

        return response([
            'success' => true,
            'linker' => $this->module->settingEnabled(FacesModule::SETTING_LINKING_NAME) && ($linked === null)
                ? [
                    'url' => route(LinkMediaToRecordAction::class, [
                        'tree' => $media->tree()->name(),
                        'xref' => $media->xref(),
                    ]),
                    'data' => [
                        'link' => $pid,
                    ],
                ]
                : null,
        ]);
    }

    private function detach(Request $request, Media $media, string $fact): Response
    {
        if (!$media->canEdit()) {
            throw new HttpNotFoundException();
        }

        $pid = $request->getParsedBody()['pid'] ?? null;

        if ($pid === null) {
            throw new HttpNotFoundException();
        }

        $map = array_filter($this->getMediaMap($media, $fact), function($area) use ($pid) {
            return !empty($area['pid']) && $area['pid'] !== $pid;
        });

        $this->setMediaMap($media, $fact, $map);

        return response([
            'success' => true,
        ]);
    }

    private function getMedia(Request $request): Media
    {
        $mid = $request->getQueryParams()['mid'] ?? $request->getParsedBody()['mid'];

        if ($mid === null) {
            throw new HttpNotFoundException();
        }

        $tree = $request->getAttribute('tree');

        if (!($tree instanceof Tree)) {
            throw new HttpNotFoundException();
        }

        return Registry::mediaFactory()->make($mid, $tree);
    }

    private function getFact(Request $request): string
    {
        $fact = $request->getQueryParams()['fact'] ?? $request->getParsedBody()['fact'];

        if ($fact === null) {
            throw new HttpNotFoundException();
        }

        return $fact;
    }

    private function getPeopleOnPhoto(Tree $tree, array $areas): array
    {
        $pids = array_map(fn($area) => $area['pid'], $areas);
        $individualFactory = Registry::individualFactory();
   
        return $this->module->query->getIndividualsDataByTreeAndPids((string)$tree->id(), $pids)
            ->map(fn($row) => $individualFactory->make($row->xref, $tree, $row->gedcom))
            ->filter(fn($person) => $person !== null)
            ->toArray();
    }

    private function getMediaMapForTree(Media $media, array $areas, array $people): array
    {
        $priorFact = $this->getMediaFacts($media)->first();

        $result = [];
        foreach ($areas as $area) {
            $pid = (string) $area['pid'];
            $result[$pid] = [
                'link' => null,
                'pid' => $pid,
                'name' => $pid,
                'life' => '',
                'coords' => $area['coords'],
            ];
        }

        foreach ($people as $person) {
            $xref = $person->xref();
            $public = $person->canShowName();
            $result[$xref] = array_merge($result[$xref], [
                'link' => $public
                    ? $person->url()
                    : null,
                'name' => $public
                    ? $this->getPersonDisplayName($person, $priorFact)
                    : I18N::translate('Private'),
                'age' => $public
                    ? $this->getPersonDisplayAgePhoto($person, $priorFact)
                    : I18N::translate('Private'),
                'life' => $public
                    ? strip_tags($person->lifespan())
                    : I18N::translate('Private'),
            ]);
        }
        usort($result, function($compa, $compb) {
            return $compa['coords'][0] - $compb['coords'][0];
        });

        return $result;
    }

    private function getPersonDisplayAgePhoto(Individual $person, ?Fact $fact): string
    {
        if ($fact === null) {
            return '';
        }

        $birthDate = $person->getBirthDate();
        $factDate = $fact->date();
        if ($birthDate === null || $factDate === null) {
            return '';
        }

        return I18N::translate('Age at') . (string) new Age($birthDate, $factDate);
    }

    private function getPersonDisplayName(Individual $person, ?Fact $fact): string
    {
        return html_entity_decode(strip_tags(str_replace([
            '<q class="wt-nickname">',
            '</q>',
        ], '"', $person->fullName())), ENT_QUOTES);
    }

    private function getMediaTitle(Media $media, string $fact): string
    {
        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);

        if ($file === null) {
            throw new HttpNotFoundException();
        }

        return !empty($file->title())
            ? $file->title()
            : $file->filename();
    }

    private function getMediaFacts(Media $media): Collection
    {
        $mediaFileFacts = $media->facts(['FILE']);
        $linkedRecords = $this->links->allLinkedRecords($media)
            ->flatMap(function($individual) use ($media) {
                return $individual
                    ->facts()
                    ->filter(function(Fact $fact) use ($media) {
                        return collect(FactWrapper::getMedia($fact))->filter(function(Media $m) use ($media) {
                            return $media->xref() === $m->xref();
                        })->isNotEmpty();
                    });
            });

        return $mediaFileFacts
            ->concat($linkedRecords)
            ->filter(fn($fact) => $fact->attribute('DATE') !== '' || $fact->attribute('PLAC') !== '')
            ->unique(function(Fact $fact) {
                return $fact->attribute('DATE');
            });
    }

    private function getMediaMeta(Media $media): array
    {
        return $this->getMediaFacts($media)
            ->map(function(Fact $fact) {
                $meta = [];
                foreach (['DATE', 'PLAC'] as $name) {
                    $value = $fact->attribute($name);
                    if ($value !== '') {
                        $meta[$name] = $value;
                    }
                }
                return $meta;
            })
            ->toArray();
    }

    private function getMediaMap(Media $media, string $fact): array
    {
        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);

        if ($file === null) {
            throw new HttpNotFoundException();
        }

        if (($map = $this->module->query->getMediaMap(
            $media->tree()->id(),
            $media->xref(),
            $order
        )) !== null) {
            return json_decode($map, true);
        }

        if ($this->module->settingEnabled(FacesModule::SETTING_EXIF_NAME)) {
            $path = Webtrees::DATA_DIR . $media->tree()->getPreference('MEDIA_DIRECTORY') . $file->filename();

            return (new ExifHelper())->getMediaMap($path) ?: [];
        }

        return [];
    }

    private function setMediaMap(Media $media, string $fact, array $map = []): void
    {
        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);

        if ($file === null) {
            throw new HttpNotFoundException();
        }

        $this->module->query->setMediaMap(
            $media->tree()->id(),
            $media->xref(),
            $order,
            $file->filename(),
            empty($map) ? null : json_encode($map)
        );
    }
}
