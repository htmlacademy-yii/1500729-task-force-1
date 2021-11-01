<?php


namespace taskforce\app;


class ActionDecline extends AbstractTaskAction
{

    protected string $actionName = 'Отказаться';
    protected string $action = 'refusal';

    public function canUse(?int $executorId, int $userId, int $authorId, int $status): bool
    {
        if ($status === task::STATUS_IN_WORK && $userId === $executorId) {

            return true;
        }
        return false;
    }
}
