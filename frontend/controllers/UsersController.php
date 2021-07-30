<?php

namespace frontend\controllers;

use frontend\models\Responds;
use frontend\models\Reviews;
use frontend\models\Users;
use yii\db\Query;

class UsersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $users = Users::find()->where(['role' => Users::ROLE_EXECUTOR])
            ->with('executorCategories.category')->with('tasks0.reviews')
            ->orderBy('dt_add DESC')->all();

        return $this->render('index', ['users' => $users]);
    }
}
