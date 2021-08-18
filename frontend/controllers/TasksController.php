<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use frontend\models\Users;
use yii\web\Controller as ControllerAlias;


class TasksController extends ControllerAlias
{
    public function actionIndex()
    {
        $tasks = Tasks::find()->where(['status' => Tasks::STATUS_NEW])
            ->orderBy(['dt_add' => SORT_DESC])
            ->with('category')
            ->with('location')->all();

        return $this->render('tasks', ['tasks' => $tasks]);
    }

}
