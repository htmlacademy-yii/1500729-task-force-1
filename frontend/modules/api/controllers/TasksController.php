<?php

namespace frontend\modules\api\controllers;

use frontend\models\Tasks;

class TasksController extends \yii\rest\ActiveController
{
     public $modelClass = Tasks::class;

     public function actions()
     {
         $actions = parent::actions();
         unset($actions['delete'], $actions['create']);
     }

    public function actionIndex() {

         return Tasks::find()->where(['executor_id' => \Yii::$app->user->getId()])->all();
     }
}
