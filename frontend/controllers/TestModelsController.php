<?php


namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\Locations;
use Yii;
use yii\web\Controller;


class TestModelsController extends Controller
{
    public function actionIndex()
    {
        $users = Users::find()->joinWith(['location l'])->
        select('name, location_id')->limit(5)->all();

        foreach ($users as $user) {
            $locations = $user->location->location;
            echo '<pre>';
            print_r($locations);
            echo '</pre>';
            echo '<pre>';
            print_r($user);
            echo '</pre>';

        }

    }
}
