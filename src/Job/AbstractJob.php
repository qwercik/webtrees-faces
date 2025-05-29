<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Job;

use Komputeryk\Webtrees\JobQueue\Job;
use Throwable;

abstract class AbstractJob
{
    abstract function handleTask(Job $task): void;

    public function run(array $jobs): array
    {
        $results = [];
        foreach ($jobs as $task) {
            $results[$task->id] = $this->handleTaskSafe($task);
        }

        return $results;
    }

    protected function handleTaskSafe(Job $task): array
    {
        try {
            $this->handleTask($task);
            return ['status' => 'success'];
        } catch (Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
