<?php


namespace taskforce\app;


abstract class AbstractTaskAction
{
    protected string $actionName;
    protected string $action;

    public function getActionName(): string
    {
        return $this->actionName;
    }


    public function getAction(): string
    {
        return $this->action;
    }


    abstract protected function canUse(int $executorId, int $userId, int $authorId, int $status);
}

