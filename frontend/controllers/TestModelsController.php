<?php


namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\Locations;
use Yii;
use yii\web\Controller;


class TestModelsController extends Controller
{
   public function actionIndex() {
       $users = Users::find()->select('name, phone')->joinWith('location')->one();
       foreach ($users as $user) {
           print($user);
       }

   }
}
