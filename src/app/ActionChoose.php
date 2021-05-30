<?php


namespace taskforce\app;


class ActionChoose extends AbstractTaskAction
{

    public function getActionName(): string
    {
        return 'Выбрать исполнителя';
    }

    public function getAction(): string
    {
        return 'action_choose';
    }

    public function compareID(int $executorId, int $userId, int $clientId): bool
    {
        if ($clientId === $userId) {
            return true;
        } else {
            return false;
        }
    }
}
