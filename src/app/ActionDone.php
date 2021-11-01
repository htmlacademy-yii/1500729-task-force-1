<?php


namespace taskforce\app;


class ActionDone extends AbstractTaskAction
{

    protected string $actionName = 'Завершить';
    protected string $action = 'request';

    public function canUse(?int $executorId, int $userId, int $authorId, int $status): bool
    {
        if ($userId === $authorId && $status === task::STATUS_IN_WORK) {
            return true;
        }
        return false;
    }
}
