<?php

class TaskClass
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'action_cancel';
    const ACTION_RESPOND = 'action_respond';
    const ACTION_DONE = 'action_done';
    const ACTION_DECLINE = 'action_decline';
    const ACTION_CHOOSE = 'action_choose';


    public ?int $clientId;
    public ?int $executorId;

    public ?string $status;
    public ?string $action;


    public function getTaskMap(): array
    {
        return [
            self::STATUS_NEW => 'Новая задача',
            self::STATUS_CANCEL => 'Задача отменена',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_DONE => 'Задача выполнена',
            self::STATUS_FAILED => 'Задача провалена',
            self::ACTION_CANCEL => 'Отменить задачу',
            self::ACTION_RESPOND => 'Взять в работу',
            self::ACTION_DONE => 'Выполнить задачу',
            self::ACTION_DECLINE => 'Отказаться',
            self::ACTION_CHOOSE => 'Выбрать исполнителя'
        ];
    }

    public function __construct($clientId, $executorId)
    {
        $this->clientId = $clientId;
        $this->executorId = $executorId;
    }

    public function getNewStatus($status, $action): string
    {
        switch ($status) {
            case self::STATUS_NEW:
                if ($action == self::ACTION_CANCEL) {
                    $status = self::STATUS_CANCEL;
                }
                if ($action == self::ACTION_RESPOND) {
                    $status = self::STATUS_NEW;
                }
                if ($action == self::ACTION_CHOOSE) {
                    $status = self::STATUS_IN_WORK;
                }
                break;
            case self::STATUS_IN_WORK:
                if ($action == self::ACTION_DONE) {
                    $status = self::STATUS_DONE;
                }
                if ($action == self::ACTION_DECLINE) {
                    $status = self::STATUS_FAILED;
                }
                break;
            default:
                return $error = 'Невозможно выполнить действие из данного статуса';
        }
        return $status;
    }

    public function getActiveActions($status)
    {
        switch ($status) {
            case self::STATUS_NEW:
                $activeActions = [
                    self::ACTION_RESPOND,
                    self::ACTION_CANCEL,
                    self::ACTION_CHOOSE
                ];
                break;
            case self::STATUS_IN_WORK:
                $activeActions = [
                    self::ACTION_DONE,
                    self::ACTION_DECLINE
                ];
                break;
            default:
                return $error = 'Нет доступных действий для этого статуса';

        }
        return $activeActions;
    }
}
