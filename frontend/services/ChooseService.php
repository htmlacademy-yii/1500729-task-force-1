<?php

namespace frontend\services;

use frontend\models\Notifications;
use frontend\models\Users;
use Yii;

class ChooseService
{
    public function sendNotification($taskId, $recipientId)
    {
        $recipient = Users::findOne($recipientId);
        try {
            $notification = $this->newNotification($taskId, $recipientId);
            if ($recipient->notice_new_action == 1) {
                $this->sendEmail($notification);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function newNotification($taskId, $recipientId)
    {
        $notification = new Notifications();
        $notification->task_id = $taskId;
        $notification->recipient_id = $recipientId;
        $notification->type = 'executor';
        if ($notification->validate()) {
            $notification->save();
            return $notification;
        }
    }

    private function sendEmail($notification)
    {
        Yii::$app->mailer->compose('_choose', ['notification' => $notification])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($notification->recipient->email)
            ->setSubject('Вас выбрали исполнителем')
            ->send();
    }
}
