<?php

namespace frontend\services;

use frontend\models\Notifications;
use Yii;

class MessageService
{
      public function sendNotification($taskId, $recipientId) {
          try {
              $notification = $this->newNotification($taskId, $recipientId);
              $this->sendEmail($notification);
          } catch (\Exception $e) {
              throw $e;
          }
      }

      private function newNotification($taskId, $recipientId) {
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
