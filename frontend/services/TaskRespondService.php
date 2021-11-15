<?php

namespace frontend\services;

use frontend\models\Notifications;
use frontend\models\Responds;
use frontend\models\Tasks;
use Yii;
use yii\db\Exception;
use yii\web\BadRequestHttpException;

class TaskRespondService
{
    public function execute(?object $respondAuthor, Responds $model, array $data, int $task_id):void
    {
        if (!$respondAuthor) {
            $model->task_id = $task_id;
            $model->executor_id = Yii::$app->user->identity->getId();
            $model->load($data);
            if ($model->validate()) {
                $model->save();
            }
            $this->sendNotification($task_id);
        } else {
            throw new BadRequestHttpException('Вы уже откликались на это задание');
        }

    }
    private function sendNotification($task_id) {
        $notification = new Notifications();
        $notification->task_id = $task_id;
        $notification->recipient_id = Tasks::findOne($task_id)->author_id;
        $notification->type = 'respond';
        if ($notification->validate()) {
            $notification->save();
            $this->sendEmail($notification);
        } else {
            throw new Exception("Не удалось отправить уведомление");
        }
    }
    private function sendEmail($notification) {
        Yii::$app->mailer->compose('_respond', ['notification' => $notification])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($notification->recipient->email)
            ->setSubject('Новый отклик к заданию')
            ->send();
    }
}
