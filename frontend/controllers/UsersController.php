<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\FilterUsers;
use frontend\models\Responds;
use frontend\models\Reviews;
use frontend\models\Users;
use frontend\services\UserFilterService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    public function actionIndex()
    {
           $model = new FilterUsers();
        $categories = Categories::find()->all();
        $users = Users::find()->where(['role' => Users::ROLE_EXECUTOR])
            ->with('executorCategories.category')->with('executeTasks.reviews')
            ->joinWith('executeTasks')
            ->with('executorCategories')->distinct();

        if (Yii::$app->request->get()) {
            $users = (new UserFilterService())->filterUsers($users, Yii::$app->request->get(), $model);
        }
        
        $usersProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 5
            ]
        ]);
        return $this->render('index', ['dataProvider' => $usersProvider, 'model' => $model, 'categories' => $categories]);
    }

    public function actionView($id) {
        $user = Users::find()->where(['users.id' =>$id])->joinWith('location')
            ->joinWith('executeTasks')->with('executorCategories.category')
            ->with('workPhotos.files')->one();
        $reviews = Reviews::find()->joinWith('task.author')
            ->where(['executor_id' => $id])->all();

        if(!$user) {
            throw new NotFoundHttpException("Контакт с ID {$id} не найден");
        }

        return $this->render('view', ['user' => $user, 'reviews' => $reviews]);
    }
}
