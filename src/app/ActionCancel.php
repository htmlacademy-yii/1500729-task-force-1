<?php


namespace taskforce\app;


class ActionCancel extends AbstractTaskAction
{

    public function getActionName(): string
    {
        return 'Отменить задачу';
    }

    public function getAction(): string
    {
        return 'action_cancel';
    }

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($clientId === $userId) {
            return true;
        } else {
            return false;
        }
    }
}
