<?php

namespace taskforce\app;

use taskforce\exceptions\ActionException;
use taskforce\exceptions\StatusException;


class Task
{

    const STATUS_NEW = 0;
    const STATUS_CANCEL = 1;
    const STATUS_IN_WORK = 2;
    const STATUS_DONE = 3;
    const STATUS_FAILED = 4;

    public int $authorId;
    public ?int $executorId;

    public ?string $status;

    private ActionCancel $actionCancel;
    private ActionChoose $actionChoose;
    private ActionRespond $actionRespond;
    private ActionDone $actionDone;
    private ActionDecline $actionDecline;

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
            $this->actionCancel->getAction() => $this->actionCancel->getActionName(),
            $this->actionRespond->getAction() => $this->actionRespond->getActionName(),
            $this->actionDone->getAction() => $this->actionDone->getActionName(),
            $this->actionDecline->getAction() => $this->actionDecline->getActionName(),
            $this->actionChoose->getAction() => $this->actionChoose->getActionName()
        ];
    }

    /**
     * TaskClass constructor.
     * @param int $authorId - ID заказчика
     * @param ?int $executorId - ID исполнителя
     */
    public function __construct(int $authorId, ?int $executorId )
    {
        $this->actionCancel = new ActionCancel();
        $this->actionChoose = new ActionChoose();
        $this->actionRespond = new ActionRespond();
        $this->actionDone = new ActionDone();
        $this->actionDecline = new ActionDecline();
        $this->authorId = $authorId;
        $this->executorId = $executorId;

    }

    /**
     * Получает статус, в который он перейдет, после совершения указанного действия
     * @param string $action - действие для статуса
     * @return string - возвращает статус задачи, в который она перейдет после выполнения указанного действия
     * @throws actionException - если указано несуществующее действие или
     * действие из которого невозможно получение нового статуса.
     */
    public function getNewStatus(string $action): string
    {
        switch ($action) {
            case $this->actionCancel->getAction():
                $newStatus = self::STATUS_CANCEL;
                break;
            case $this->actionDone->getAction():
                $newStatus = self::STATUS_DONE;
                break;
            case $this->actionDecline->getAction():
                $newStatus = self::STATUS_FAILED;
                break;
            case $this->actionChoose->getAction():
                $newStatus = self::STATUS_IN_WORK;
                break;
            default:
                throw new ActionException("Неверно указано действие");
        }
        return $newStatus;
    }

    /**
     * Возвращает доступные действия для указанного статуса и пользователя
     * @param int $status - статус задачи
     * @param ?int $userId - ID пользователя
     * @return array возвращает массив с доступными действиями
     * @throws statusException - указано несуществующий статус
     */
    public function getActiveActions(int $status, int $userId): array
    {
        if (!array_key_exists($status, $this->getTaskMap())) {
            throw new StatusException("Такого статуса не существует");
        }
         $activeActions = [];

                if ($this->actionRespond->canUse($this->executorId, $userId, $this->authorId, $status)) {
                    $activeActions[] = [ $this->actionRespond->getAction() => $this->actionRespond->getActionName()];
                }
                if ($this->actionCancel->canUse($this->executorId, $userId, $this->authorId, $status)) {
                    $activeActions[] = [$this->actionCancel->getAction() => $this->actionCancel->getActionName()];
                }
                if ($this->actionDone->canUse($this->executorId, $userId, $this->authorId, $status)) {
                    $activeActions[] = [$this->actionDone->getAction() => $this->actionDone->getActionName()];
                }
                if ($this->actionDecline->canUse($this->executorId, $userId, $this->authorId, $status)) {
                    $activeActions[] = [$this->actionDecline->getAction() => $this->actionDecline->getActionName()];
                }


        return $activeActions;
    }
}
