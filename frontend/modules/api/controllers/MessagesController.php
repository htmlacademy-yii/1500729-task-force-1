<?php

namespace frontend\modules\api\controllers;

use frontend\models\Messages;
use yii\rest\ActiveController;
use yii\web\Controller;

class MessagesController extends ActiveController
{
    public $modelClass = Messages::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);

        return $actions;
    }

    public function actionCreate() {
        $body = json_decode(\Yii::$app->request->getRawBody());
        $newMessage = new $this->modelClass();
        $newMessage->content = $body->content;
        $newMessage->task_id = $body->task_id;
        $newMessage->sender_id = $body->sender_id;
        $newMessage->recipient_id = $body->recipient_id;
        $newMessage->dt_add = date('Y-m-d H:i:s');
        if ($newMessage->save()) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            return $newMessage;
        }



    }

}


