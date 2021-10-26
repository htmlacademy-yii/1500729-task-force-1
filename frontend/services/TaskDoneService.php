<?php

namespace frontend\services;

class TaskDoneService
{
    public function execute(array $data): void
    {
        $this->createReview($data);
        $this->updateTask();
    }

    private function createReview(array $data)
    {
        // ...
    }

    private function updateTask()
    {
        // ...
    }
}
