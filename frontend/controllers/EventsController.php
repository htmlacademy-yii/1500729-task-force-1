<?php

namespace frontend\controllers;

use frontend\models\Notifications;
use PHPUnit\Exception;

class EventsController extends SecuredController
{
    public function actionIndex() {
        $userId = \Yii::$app->user->id;
        try {

            $notifications = Notifications::find()->where(['read' => Notifications::UNREAD])->andWhere(['recipient_id' => $userId])->all();
            foreach ($notifications as $notification) {
                $notification->read = Notifications::READ;
                $notification->save();
            }
        } catch (\Exception $e) {
            $this->setStatusCode(404);
            throw $e;
        }
    }
}
