<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Job;

use Komputeryk\Webtrees\JobQueue\Job;
use Komputeryk\Webtrees\JobQueue\JobQueueRepository;
use UksusoFF\WebtreesModules\Faces\Modules\FacesModule;
use UksusoFF\WebtreesModules\Faces\Repository\MediaFileRepository;
use UksusoFF\WebtreesModules\Faces\SznupaApi;

final class SznupaSchedulingJob extends AbstractJob
{
    const LIMIT = 100;

    protected MediaFileRepository $mediaFileRepository;
    protected $alreadyExecuted = false;

    public function __construct(FacesModule $module)
    {
        $this->mediaFileRepository = new MediaFileRepository($module);
    }

    public function handleTask(Job $task): void
    {
        if ($this->alreadyExecuted) {
            return;
        }

        $this->alreadyExecuted = true;
        SznupaApi::create();
        JobQueueRepository::schedule('sznupa-scheduling');
        if (JobQueueRepository::isAnyTaskPending('sznupa-indexing')) {
            return;
        }

        $ids = $this->mediaFileRepository->findUnindexedMediaFiles(self::LIMIT);
        foreach ($ids as $id) {
            JobQueueRepository::schedule('sznupa-indexing', ['f_id' => $id]);
        }
    }
}
