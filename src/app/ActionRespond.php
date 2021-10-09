<?php


namespace taskforce\app;


class ActionRespond extends AbstractTaskAction
{

    protected string $actionName = 'Откликнуться';
    protected string $action = 'response';

    public function canUse(?int $executorId, int $userId, int $authorId, int $status): bool
    {
        if ($userId !== $executorId && $userId !== $authorId && $status === task::STATUS_NEW) {
            return true;
        }
        return false;
    }
}
