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
use Fisharebest\Webtrees\MediaFile;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Services\LinkedRecordService;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Webtrees;
use Illuminate\Support\Collection;
use Komputeryk\Webtrees\JobQueue\JobQueueRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use UksusoFF\WebtreesModules\Faces\Entity\MediaFileData;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaException;
use UksusoFF\WebtreesModules\Faces\Helpers\AppHelper;
use UksusoFF\WebtreesModules\Faces\Helpers\DatabaseHelper;
use UksusoFF\WebtreesModules\Faces\Helpers\ExifHelper;
use UksusoFF\WebtreesModules\Faces\Helpers\SznupaHelper;
use UksusoFF\WebtreesModules\Faces\Modules\FacesModule;
use UksusoFF\WebtreesModules\Faces\Repository\MediaFileRepository;
use UksusoFF\WebtreesModules\Faces\SznupaApi;
use UksusoFF\WebtreesModules\Faces\Wrappers\FactWrapper;

class DataController implements RequestHandlerInterface
{
    public const ROUTE_PREFIX = 'faces-data';

    protected FacesModule $module;

    protected LinkedRecordService $links;
    protected MediaFileRepository $mediaFileRepository;
    protected DatabaseHelper $facesDbHelper;

    public function __construct(FacesModule $module)
    {
        $this->module = $module;

        $this->links = AppHelper::get(LinkedRecordService::class);
        $this->mediaFileRepository = new MediaFileRepository($module);
        $this->facesDbHelper = new DatabaseHelper();
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
                case 'sznupa-reset':
                    return $this->sznupaReset($request, $media, $fact);
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

        $useSznupa = $request->getQueryParams()['sznupa'] ?? null;
        if ($useSznupa !== null) {
            SznupaHelper::setEnabled(boolval($useSznupa));
        }

        $tree = $media->tree();
        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);
        if ($file === null) {
            throw new HttpNotFoundException();
        }

        $data = $this->mediaFileRepository->getData($media, $order);
        $faces = $this->getFaces($tree, $media, $file, $data);
        $people = $this->getPeopleOnPhoto($tree, $faces);

        $estimatedDateRangeStart = $this->estimateDateRangeStart($people);
        $estimatedDateRangeEnd = $this->estimateDateRangeEnd($people);

        return response([
            'success' => true,
            'url' => $media->url(),
            'note' => $media->getNote(),
            'type' => $this->getMediaType($media),
            'title' => $this->getMediaTitle($media, $fact),
            'meta' => $this->module->settingEnabled(FacesModule::SETTING_META_NAME)
                ? $this->getMediaMeta($media)
                : [],
            'map' => $this->getMediaMapForTree($media, $faces, $people),
            'edit' => $media->canEdit(),
            'estimated_date_range' => $this->formatDateRange($estimatedDateRangeStart, $estimatedDateRangeEnd),
            'sznupa' => [
                'enabled' => SznupaHelper::isEnabled(),
                'warning' => SznupaHelper::popWarning() ?? '',
                'labels' => $this->getSznupaLabels(),
                'indexed' => $data?->sznupaId !== null,
            ],
        ]);
    }

    private function getMediaType(Media $media): ?string
    {
        if (preg_match('/\n3 TYPE (.+)/', $media->gedcom(), $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function getSznupaLabels(): array
    {
        $labels = [];
        foreach (['LBL_SZNUPA_ACTIVATE', 'LBL_SZNUPA_ANALYZE', 'LBL_SZNUPA_ANALYZE_AGAIN', 'LBL_SZNUPA_ANALYZE_AGAIN_TITLE'] as $label) {
            $labels[$label] = I18N::translate($label);
        }
        return $labels;
    }

    private function getFaces(Tree $tree, Media $media, MediaFile $file, ?MediaFileData $data): array
    {
        if ($data === null) {
            SznupaHelper::setWarning(I18N::translate('LBL_SZNUPA_NO_DATA'));
            return [];
        }

        return $this->getFacesFromSznupa($tree, $data)
            ?? $this->getFacesFromWebtrees($data)
            ?? $this->getFallbackMediaFileMap($media, $file);
    }

    private function getFacesFromSznupa(Tree $tree, MediaFileData $data): ?array
    {
        if (!SznupaHelper::isEnabled()) {
            return null;
        }

        if ($data->sznupaId === null) {
            SznupaHelper::setWarning(I18N::translate('LBL_SZNUPA_NOT_INDEXED'));
            return null;
        }

        try {
            return $this->fetchFacesFromSznupa($tree, $data->sznupaId);
        } catch (SznupaException $e) {
            SznupaHelper::setEnabled(false);
            SznupaHelper::setWarning($e->getMessage());
        }

        return null;
    }

    private function fetchFacesFromSznupa(Tree $tree, string $sznupaId): array
    {
        $api = SznupaApi::create();
        $faces = $api->fetchFaces($sznupaId);
        $individuals = $this->getIndividualsForGuess($tree, $faces);

        $result = [];
        foreach ($faces as $face) {
            $pid = $face['pid'] ?? "sznupa/{$face['id']}";
            $area = $face['area'];
            $left = (int)$area['left'];
            $top = (int)$area['top'];
            $width = (int)$area['width'];
            $height = (int)$area['height'];
            $result[$pid] = [
                'coords' => [$left, $top, $left + $width, $top + $height],
                'life' => '',
                'link' => null,
                'name' => $face['pid'] ?? '?',
                'pid' => $pid,
                'sznupa_id' => $face['id'],
                'guess' => array_map(function ($guess) use ($individuals) {
                    $pid = $guess['pid'];
                    $probability = (int)($guess['score'] * 100);

                    return [
                        'pid' => $guess['pid'],
                        'text' => view('selects/individual', [
                            'individual' => $individuals[$pid] ?? $pid,
                            'probability' => $probability,
                        ]),
                    ];
                }, $face['guess'])
            ];
        }

        return $result;
    }

    private function getFacesFromWebtrees(MediaFileData $data): array
    {
        $faces = [];
        foreach ($data->coordinates ?? [] as $area) {
            $pid = (string) $area['pid'];
            $faces[$pid] = [
                'link' => null,
                'pid' => $pid,
                'name' => $pid,
                'life' => '',
                'coords' => $area['coords'],
            ];
        }

        return $faces;
    }

    private function getIndividualsForGuess(Tree $tree, array $faces): array
    {
        $pids = [];
        foreach ($faces as $face) {
            foreach ($face['guess'] as $guess) {
                $pids[] = $guess['pid'];
            }
        }

        $individuals = [];
        $factory = Registry::individualFactory();
        foreach (array_unique($pids) as $pid) {
            $individuals[$pid] = $factory->make($pid, $tree);
        }
        return $individuals;
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
        $birthDates = array_filter(array_map(fn($person) => $person->getBirthDate(), $people), fn($date) => $date->isOk());
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

        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);
        if ($file === null) {
            throw new HttpNotFoundException();
        }

        $data = $this->mediaFileRepository->getData($media, $order);
        $map = $data->coordinates ?? $this->getFallbackMediaFileMap($media, $file);
        $this->upsertFace($map, $pid, $coords);
        $this->setMediaMap($media, $fact, $map);
        $data = $this->mediaFileRepository->getData($media, $order);
        $this->updateImageInSznupa($file, $data);

        $linked = $this->links->linkedIndividuals($media, 'OBJE')->first(function (Individual $individual) use ($pid) {
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

        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);
        if ($file === null) {
            throw new HttpNotFoundException();
        }

        $data = $this->mediaFileRepository->getData($media, $order);
        if ($data === null) {
            throw new HttpNotFoundException();
        }

        $coordinates = $data->coordinates ?? $this->getFallbackMediaFileMap($media, $file);
        $map = array_filter($coordinates, function ($area) use ($pid) {
            return !empty($area['pid']) && $area['pid'] !== $pid;
        });
        $this->setMediaMap($media, $fact, $map);

        $data->coordinates = $map;
        $this->deleteFaceFromSznupa($data, $pid);
        return response([
            'success' => true,
        ]);
    }

    private function sznupaReset(Request $request, Media $media, string $fact): Response
    {
        if (!$media->canEdit()) {
            throw new HttpNotFoundException();
        }

        [$file, $order] = $this->module->media->getMediaImageFileByFact($media, $fact);
        if ($file === null) {
            throw new HttpNotFoundException();
        }

        $data = $this->mediaFileRepository->getData($media, $order);
        if ($data === null) {
            $this->createMissingDataRow($media, $file, $order);
            $data = $this->mediaFileRepository->getData($media, $order);
        }

        $params = ['f_id' => $data->id];
        if ($data->sznupaId !== null) {
            $params['regenerate'] = true;
            SznupaHelper::setWarning(I18N::translate('LBL_SZNUPA_ANALYZE_QUEUED'));
        }

        JobQueueRepository::schedule('sznupa-indexing', $params);

        return response([
            'success' => true,
        ]);
    }

    private function createMissingDataRow(Media $media, MediaFile $file, int $order): void
    {
        $this->facesDbHelper->setMediaMap(
            $media->tree()->id(),
            $media->xref(),
            $order,
            json_encode([]),
            $file->filename()
        );
    }

    private function upsertFace(array &$map, string $pid, array $coords): void
    {
        foreach ($map as &$face) {
            if ($this->coordsEqual($face['coords'], $coords)) {
                $face['pid'] = $pid;
                return;
            }
        }

        $map[] = [
            'pid' => $pid,
            'coords' => $coords,
        ];
    }

    private function coordsEqual(array $a, array $b, int $threshold = 5): bool
    {
        for ($i = 0; $i < 4; $i++) {
            if (abs($a[$i] - $b[$i]) > $threshold) {
                return false;
            }
        }

        return true;
    }

    private function deleteFaceFromSznupa(MediaFileData $data, string $pid): void
    {
        $sznupaFaceId = str_starts_with($pid, 'sznupa/') ? substr($pid, 7) : null;

        try {
            SznupaHelper::deleteFace($data, $pid, $sznupaFaceId);
            return;
        } catch (SznupaException) {
            // Ignore errors, just remove from map
        }

        $params = ['f_id' => $data->id];
        if ($sznupaFaceId !== null) {
            $params['del_face_id'] = $sznupaFaceId;
        } else {
            $params['del_pid'] = $pid;
        }
        JobQueueRepository::schedule('sznupa-indexing', $params);
    }

    private function updateImageInSznupa(MediaFile $file, MediaFileData $data): void
    {
        if ($data->sznupaId !== null) {
            try {
                SznupaHelper::updateImage($data->sznupaId, $file->media()->tree(), $file, $data);
                return;
            } catch (SznupaException) {
                // Empty catch block
                // Try to schedule instead
            }
        }

        JobQueueRepository::schedule('sznupa-indexing', ['f_id' => $data->id]);
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

    private function getMediaMapForTree(Media $media, array $faces, array $people): array
    {
        $priorFact = $this->getMediaFacts($media)->first();

        foreach ($people as $person) {
            $xref = $person->xref();
            $public = $person->canShowName();
            $faces[$xref] = array_merge($faces[$xref], [
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

        usort($faces, function ($compa, $compb) {
            return $compa['coords'][0] - $compb['coords'][0];
        });

        return $faces;
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
            ->flatMap(function ($individual) use ($media) {
                return $individual
                    ->facts()
                    ->filter(function (Fact $fact) use ($media) {
                        return collect(FactWrapper::getMedia($fact))->filter(function (Media $m) use ($media) {
                            return $media->xref() === $m->xref();
                        })->isNotEmpty();
                    });
            });

        return $mediaFileFacts
            ->concat($linkedRecords)
            ->filter(fn($fact) => $fact->attribute('DATE') !== '' || $fact->attribute('PLAC') !== '')
            ->unique(function (Fact $fact) {
                return $fact->attribute('DATE');
            });
    }

    private function getMediaMeta(Media $media): array
    {
        return $this->getMediaFacts($media)
            ->map(function (Fact $fact) {
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

    private function getFallbackMediaFileMap(Media $media, MediaFile $file): array
    {
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
            json_encode($map),
            $file->filename()
        );
    }
}
