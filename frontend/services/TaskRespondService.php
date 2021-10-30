<?php

namespace frontend\services;

use frontend\models\Responds;
use Yii;
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
        } else {
            throw new BadRequestHttpException('Вы уже откликались на это задание');
        }
    }
}
