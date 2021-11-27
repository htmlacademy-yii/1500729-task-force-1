<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\Tasks;
use frontend\models\Users;
use frontend\services\MyTasksService;
use yii\data\ActiveDataProvider;

class MyTasksController extends SecuredController
{
     public function actionIndex() {
         $user = Users::findOne(\Yii::$app->user->id);

         if ($user->role === Users::ROLE_AUTHOR) {
         $query = Tasks::find()->where(['author_id' => $user->id]);
             } else {
             $query = Tasks::find()->where(['executor_id' => $user->id]);
         }
         if (\Yii::$app->request->get()) {
             if (\Yii::$app->request->get('status') == Tasks::STATUS_OVERDUE ) {
                 $time = new \DateTime('now');
                 $time = $time->format('Y-m-d H:i:s');
                 $query->andFilterWhere(['<=', 'due_date', $time ])->andFilterWhere(['status' => Tasks::STATUS_IN_WORK]);
             } else {
            $query = $query->andFilterWhere(['status' => \Yii::$app->request->get('status')]);
             }
         }

         $tasksProvider = new ActiveDataProvider([
             'query' => $query,
             'pagination' => [
                 'pageSize' => 5
             ]
         ]);

         return $this->render('mylist', ['dataProvider' => $tasksProvider]);
     }
}
