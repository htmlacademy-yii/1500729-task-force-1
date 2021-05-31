<?php


namespace taskforce\app;


class ActionRespond extends AbstractTaskAction
{

    protected string $actionName = 'Взять в работу';
    protected string $action = 'action_respond';

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($userId !== $executorId && $userId !== $clientId) {
            return true;
        } else {
            return false;
        }
    }
}
