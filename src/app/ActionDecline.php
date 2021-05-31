<?php


namespace taskforce\app;


class ActionDecline extends AbstractTaskAction
{

    protected string $actionName = 'Отказаться';
    protected string $action = 'action_decline';

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($userId === $clientId || $userId === $executorId) {
            return true;
        } else {
            return false;
        }
    }
}
