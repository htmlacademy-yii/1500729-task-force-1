<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\FilterUsers;
use frontend\models\Responds;
use frontend\models\Reviews;
use frontend\models\Users;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UsersController extends \yii\web\Controller
{
    public $users;
    public function actionIndex()
    {
           $get = Yii::$app->request->get();
           $model = new FilterUsers();
           $model->load($get);

        $categories = Categories::find()->all();
        $this->users = Users::find()->where(['role' => Users::ROLE_EXECUTOR])
            ->with('executorCategories.category')->with('executeTasks.reviews')
            ->joinWith('executeTasks')
            ->joinWith('executorCategories')
            ->orderBy('dt_add DESC');
        if ($model->load(Yii::$app->request->get())) {
            if ($model->search) {
                $model->options = NULL;
                $model->category_id = NULL;
                $this->users = $this->users->andFilterWhere($model->getSearch());
            }
            if ($model->options && ArrayHelper::isIn(1,$model->options)) {
                $this->users = $this->users->andFilterWhere($model->getFreeExecutors());
            }
            if ($model->options && ArrayHelper::isIn(2,$model->options)) {
                $this->users = $this->users->andFilterWhere($model->getOnlineUsers());
            }
            if ($model->options && ArrayHelper::isIn(3,$model->options)) {
                $this->users = $this->users->andFilterWhere($model->getUsersWithReviews());
            }
            if ($model->options && ArrayHelper::isIn(4,$model->options)) {
                $this->users = $this->users->andFilterWhere($model->getFavouriteUsers());
            }
            if ($model->category_id) {
            $this->users = $this->users->andFilterWhere($model->filterCategories());
            }

        }
        $users = $this->users->all();

        return $this->render('index', ['users' => $users, 'model' => $model, 'categories' => $categories]);
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
