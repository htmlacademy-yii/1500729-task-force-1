<?php


namespace taskforce\app;


class ActionCancel extends AbstractTaskAction
{

    protected string $actionName = 'Отменить задачу';
    protected string $action = 'action_cancel';

    public function canUse(int $executorId, int $userId, int $authorId, string $status): bool
    {
        if ($authorId === $userId && $status === task::STATUS_NEW) {
            return true;
        }
        return false;
    }
}
