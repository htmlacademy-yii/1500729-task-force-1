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

    public static function getStars ($id) {
        $userTasksQuery = (new Query())->select('id')->from('tasks')->where('executor_id = :executor_id', [':executor_id' => $id]);
        $query = new Query();
        $query->select(['AVG(ratio)'])->from('reviews')->
        where(['task_id' => $userTasksQuery]);
         $stars = $query->one();
         if ($stars['AVG(ratio)']) {
             return round($stars['AVG(ratio)'], 2);
         } else {
             return 0;
         }
    }

    public static function getPluralTasks (int $count):string {
        if ($count == 0) {
            return "0 заданий";
        }
        if ($count == 1) {
            return "1 задание";
        }
        if ($count % 100 > 10 && $count % 100 < 20)
            return $count . " заданий";
            switch ($count % 10)
        {
            case 0: return $count . " заданий";
            case 1: return $count . " задание";
            case 2: return $count . " задания";
            case 3: return $count . " задания";
            case 4: return $count . " задания";
            case 5: return $count . " заданий";
            case 6: return $count . " заданий";
            case 7: return $count . " заданий";
            case 8: return $count . " заданий";
            case 9: return $count . " заданий";
        }
    }

    public static function getPluralReviews (int $count):string
    {
        if ($count == 0) {
            return "0 отзывов";
        }
        if ($count == 1) {
            return "1 отзыв";
        }
        if ($count % 100 > 10 && $count % 100 < 20)
            return $count . " отзывов";
        switch ($count % 10) {
            case 0:
                return $count . " отзывов";
            case 1:
                return $count . " отзыв";
            case 2:
                return $count . " отзыва";
            case 3:
                return $count . " отзыва";
            case 4:
                return $count . " отзыва";
            case 5:
                return $count . " отзывов";
            case 6:
                return $count . " отзывов";
            case 7:
                return $count . " отзывов";
            case 8:
                return $count . " отзывов";
            case 9:
                return $count . " отзывов";
        }
    }

}
