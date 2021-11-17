<?php

namespace frontend\modules\api\controllers;

use frontend\models\Messages;
use JsonSchema\Exception\JsonDecodingException;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\debug\models\timeline\DataProvider;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\helpers\Json;

class MessagesController extends ActiveController
{
    public $modelClass = Messages::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        $actions['index']['prepareDataProvider'] = [$this, 'actionIndex'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $taskId = \Yii::$app->request->get('task_id');
        $dataProvider = new ActiveDataProvider([
            'query' => Messages::find()->where(['task_id' => $taskId])

        ]);
        return $dataProvider;

    }

    public function actionIndex()
    {
        $messages = $this->prepareDataProvider()->getModels();
        $userId = \Yii::$app->user->getId();
        foreach ($messages as $key => $message) {
            $message->is_mine = $message->isMine($userId);
            $messages[$key] = $message;
        }
        return $messages;
    }

    public function actionCreate()
    {
        try {
            $body = Json::decode(\Yii::$app->request->getRawBody(), false);
        } catch (InvalidArgumentException $e) {
            throw $e;
        }

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


