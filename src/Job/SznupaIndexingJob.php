<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Job;

use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Services\GedcomImportService;
use Fisharebest\Webtrees\Services\TreeService;
use Komputeryk\Webtrees\JobQueue\Job;
use Throwable;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaUnavailableException;
use UksusoFF\WebtreesModules\Faces\Helpers\SznupaHelper;
use UksusoFF\WebtreesModules\Faces\Modules\FacesModule;
use UksusoFF\WebtreesModules\Faces\Repository\MediaFileRepository;
use UksusoFF\WebtreesModules\Faces\SznupaApi;

final class SznupaIndexingJob extends AbstractJob
{
    protected TreeService $treeService;
    protected MediaFileRepository $mediaFileRepository;
    protected bool $skip = false;

    public function __construct(
        protected FacesModule $module
    )
    {
        $this->treeService = new TreeService(new GedcomImportService);
        $this->mediaFileRepository = new MediaFileRepository($this->module);
    }

    public function handleTaskSafe(Job $task): array
    {
        if ($this->skip) {
            return ['status' => 'new'];
        }

        try {
            $this->handleTask($task);
            return ['status' => 'success'];
        } catch (SznupaUnavailableException) {
            $this->skip = true;
            return ['status' => 'new'];
        } catch (Throwable $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function handleTask(Job $task): void
    {
        $api = SznupaApi::create();
        $id = (int)$task->data['f_id'];
        $regenerate = $task->data['regenerate'] ?? false;
        $del_sznupa_id = $task->data['del_sznupa_id'] ?? null;
        $del_pid = $task->data['del_pid'] ?? null;

        $data = $this->mediaFileRepository->getDataById($id);
        if ($data === null) {
            throw new \Exception("Record with id '{$id}' not found in table wt_media_faces");
        }

        $tree = $this->treeService->find($data->treeId);
        $media = Registry::mediaFactory()->make($data->mediaId, $tree);
        $mediaFile = $media?->mediaFiles()->get($data->order);
        if ($mediaFile === null) {
            throw new \Exception("Media file with id '{$data->mediaId}' not found in tree '{$data->treeId}'");
        }

        if ($del_sznupa_id !== null || $del_pid !== null) {
            SznupaHelper::deleteFace($data, $del_pid, $del_sznupa_id);
            return;
        }

        if ($data->sznupaId !== null && !$regenerate) {
            SznupaHelper::updateImage($data->sznupaId, $tree, $mediaFile, $data);
            return;
        }

        $previousImageId = $data->sznupaId;
        $data->sznupaId = SznupaHelper::createImage($tree, $mediaFile, $data);
        $this->mediaFileRepository->setSznupaId($id, $data->sznupaId);
        if ($previousImageId !== null) {
            $api->deleteImage($previousImageId);
        }
    }
}
