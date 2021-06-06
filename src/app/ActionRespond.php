<?php


namespace taskforce\app;


class ActionRespond extends AbstractTaskAction
{

    protected string $actionName = 'Взять в работу';
    protected string $action = 'action_respond';

    public function canUse(int $executorId, int $userId, int $clientId, string $status): bool
    {
        if ($userId !== $executorId && $userId !== $clientId && $status === task::STATUS_NEW) {
            return true;
        } else {
            return false;
        }
    }
}
