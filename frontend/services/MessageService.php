<?php

namespace frontend\services;

use Exception;
use frontend\models\Notifications;
use frontend\models\Users;
use Yii;

class MessageService
{
    /**
     * @param int $taskId
     * @param int $recipientId
     * @throws Exception
     */
    public function sendNotification(int $taskId, int $recipientId)
    {
        $recipient = Users::findOne($recipientId);
        try {
            $notification = $this->newNotification($taskId, $recipientId);
            if ($recipient->notice_new_message == 1) {
                $this->sendEmail($notification);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $taskId
     * @param int $recipientId
     * @return Notifications|void
     */
    private function newNotification(int $taskId, int $recipientId): Notifications
    {
        $notification = new Notifications();
        $notification->task_id = $taskId;
        $notification->recipient_id = $recipientId;
        $notification->type = 'message';
        if ($notification->validate()) {
            $notification->save();
            return $notification;
        }
    }

    private function sendEmail($notification)
    {
        Yii::$app->mailer->compose('_message', ['notification' => $notification])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($notification->recipient->email)
            ->setSubject('Новое сообщение в чате')
            ->send();
    }
}
