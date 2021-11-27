<?php

namespace frontend\services;

use frontend\models\Notifications;
use frontend\models\RefuseTaskForm;
use frontend\models\Tasks;
use frontend\models\Users;
use Yii;

class TaskRefuseService
{
    public function execute(Tasks $task, int $executorId)
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $this->updateTask($task);
            $this->updateExecutor($executorId);
            $this->sendNotification($task->id, $task->author_id);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

    }

    private function updateTask(Tasks $task)
    {
        $task->status = Tasks::STATUS_FAILED;
        $task->save();
    }

    private function updateExecutor(int $executorId)
    {
        $executor = $executorId = Users::findOne($executorId);
        if ($executor->failed_tasks) {
            $executor->updateCounters(['failed_tasks' => 1]);
        } else {
            $executor->failed_tasks = 1;
            $executor->save();
        }
    }

    private function sendNotification($taskId, $recipientId)
    {
        $notification = new Notifications();
        $notification->task_id = $taskId;
        $notification->recipient_id = $recipientId;
        $notification->type = 'refuse';
        if ($notification->validate()) {
            $notification->save();
            if ($notification->recipient->notice_new_action == 1)
                $this->sendEmail($notification);
        }
    }

    private function sendEmail($notification)
    {
        Yii::$app->mailer->compose('_refuse', ['notification' => $notification])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($notification->recipient->email)
            ->setSubject('Исполнитель отказался от выполнения задания')
            ->send();
    }


}
