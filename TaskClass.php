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


    /**
     * Возвращает карту статусов и действий.
     * @return array[] - массив где ключ - внутреннее имя, а значение - название на русском
     */
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

    /**
     * TaskClass constructor.
     * @param int $clientId - ID заказчика
     * @param int $executorId - ID исполнителя
     */
    public function __construct(int $clientId, int $executorId)
    {
        $this->clientId = $clientId;
        $this->executorId = $executorId;
    }

    /**
     * Получает статус, в который он перейдет, после совершения указанного действия
     * @param string $action - действие для статуса
     * @return string - возвращает новый статус задачи
     */
    public function getNewStatus(string $action): string
    {
        switch ($action) {
            case self::ACTION_CANCEL:
                $this->status = self::STATUS_CANCEL;
                break;
            case self::ACTION_DONE:
                $this->status = self::STATUS_DONE;
                break;
            case self::ACTION_DECLINE:
                $this->status = self::STATUS_FAILED;
                break;
            case self::ACTION_CHOOSE:
                $this->status = self::STATUS_IN_WORK;
                break;
        }
        return $this->status;
    }

    /**
     * Возвращает доступные действия для указанного статуса и пользователя
     * @param string $status - статус задачи
     * @param int $user - ID пользователя
     * @return mixed массив с доступными действиями, string c уведомлением нет доступных действий
     */
    public function getActiveActions(string $status, int $user)
    {
        switch ($status) {
            case self::STATUS_NEW:
                if ($user == $this->clientId) {
                    $activeActions = [
                        self::ACTION_CHOOSE,
                        self::ACTION_CANCEL
                    ];
                } else {
                    $activeActions = [self::ACTION_RESPOND];
                }
                break;
            case self::STATUS_IN_WORK:
                if ($user == $this->clientId) {
                    $activeActions = [
                        self::ACTION_DONE,
                        self::ACTION_DECLINE
                    ];
                } elseif ($user == $this->executorId) {
                    $activeActions = [self::ACTION_DECLINE];
                } else {
                    return $notice = 'Нет доступных действий';
                }
                break;
            default:
                return $notice = 'Нет доступных действий';
        }
        return $activeActions;
    }
}
