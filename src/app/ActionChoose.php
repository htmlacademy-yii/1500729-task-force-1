<?php


namespace taskforce\app;


class ActionChoose extends AbstractTaskAction
{

    protected  string $actionName = 'Выбрать исполнителя';
    protected string $action = 'action_choose';

    public function canUse(int $executorId, int $userId, int $authorId, string $status): bool
    {
        if ($authorId === $userId && $status === task::STATUS_NEW) {
            return true;
        }
        return false;
    }
}
