<?php


namespace taskforce\app;


abstract class AbstractTaskAction
{

    abstract protected function getActionName();


    abstract protected function getAction();


    abstract protected function compareID(int $executorId, int $userId, int $clientId);
}

