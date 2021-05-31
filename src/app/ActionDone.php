<?php


namespace taskforce\app;


class ActionDone extends AbstractTaskAction
{

    protected string $actionName = 'Выполнить задачу';
    protected string $action = 'action_done';

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($userId === $clientId) {
            return true;
        } else {
            return false;
        }
    }
}
