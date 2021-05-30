<?php


namespace taskforce\app;


class ActionDecline extends AbstractTaskAction
{

    public function getActionName(): string
    {
        return 'Отказаться';
    }

    public function getAction(): string
    {
        return 'action_decline';
    }

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($userId === $clientId || $userId === $executorId) {
            return true;
        } else {
            return false;
        }
    }
}
