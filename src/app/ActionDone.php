<?php


namespace taskforce\app;


class ActionDone extends AbstractTaskAction
{

    public function getActionName(): string
    {
        return 'Выполнить задачу';
    }

    public function getAction(): string
    {
        return 'action_done';
    }

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($userId === $clientId) {
            return true;
        } else {
            return false;
        }
    }
}
