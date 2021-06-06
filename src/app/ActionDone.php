<?php


namespace taskforce\app;


class ActionDone extends AbstractTaskAction
{

    protected string $actionName = 'Выполнить задачу';
    protected string $action = 'action_done';

    public function canUse(int $executorId, int $userId, int $clientId, string $status): bool
    {
        if ($userId === $clientId && $status === task::STATUS_IN_WORK) {
            return true;
        } else {
            return false;
        }
    }
}
