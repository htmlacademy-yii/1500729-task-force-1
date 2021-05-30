<?php


namespace taskforce\app;


class ActionRespond extends AbstractTaskAction
{

    public function getActionName(): string
    {
        return 'Взять в работу';
    }

    public function getAction(): string
    {
        return 'action_respond';
    }

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($userId !== $executorId && $userId !== $clientId) {
            return true;
        } else {
            return false;
        }
    }
}
