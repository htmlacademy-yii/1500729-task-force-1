<?php


namespace taskforce\app;


class ActionChoose extends AbstractTaskAction
{

    protected  string $actionName = 'Выбрать исполнителя';
    protected string $action = 'action_choose';

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($clientId === $userId) {
            return true;
        } else {
            return false;
        }
    }
}
