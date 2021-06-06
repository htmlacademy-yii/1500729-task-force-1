<?php


namespace taskforce\app;


class ActionDecline extends AbstractTaskAction
{

    protected string $actionName = 'Отказаться';
    protected string $action = 'action_decline';

    public function canUse(int $executorId, int $userId, int $clientId, string $status): bool
    {
        if ($status === task::STATUS_IN_WORK) {
            if ($userId === $clientId || $userId === $executorId) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
