<?php


namespace taskforce\app;


class ActionCancel extends AbstractTaskAction
{

    protected string $actionName = 'Отменить задачу';
    protected string $action = 'action_cancel';

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($clientId === $userId) {
            return true;
        } else {
            return false;
        }
    }
}
