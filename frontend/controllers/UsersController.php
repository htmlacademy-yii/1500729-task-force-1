<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Favourites;
use frontend\models\FilterUsers;
use frontend\models\Reviews;
use frontend\models\Users;
use frontend\services\UserFilterService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UsersController extends SecuredController
{
    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex(): string
    {
        $model = new FilterUsers();
        $categories = Categories::find()->all();
        $users = Users::find()->where(['role' => Users::ROLE_EXECUTOR])
            ->with('executorCategories.category')->with('executeTasks.reviews')
            ->joinWith('executeTasks')
            ->with('executorCategories')->distinct()->andWhere(['show_profile' => 0]);

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

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $user = Users::find()->where(['users.id' =>$id])->joinWith('location')
            ->joinWith('executeTasks')->with('executorCategories.category')
            ->one();
        $reviews = Reviews::find()->joinWith('task.author')
            ->where(['executor_id' => $id])->all();

        if (!$user) {
            throw new NotFoundHttpException("Контакт с ID {$id} не найден");
        }

        return $this->render('view', ['user' => $user, 'reviews' => $reviews]);
    }

    /**
     * @param int $executor_id
     * @return Response
     * @throws Exception
     */
    public function actionFavourite(int $executor_id): Response
    {
        $favourite = new Favourites();
        $favourite->executor_id = $executor_id;
        $favourite->author_id = Yii::$app->user->id;
        if (!$favourite->save()) {
            throw new Exception('Не удалось добавить пользователя в Избранное', 500);
        } else {
            return $this->redirect(['users/view', 'id' => $executor_id]);
        }
    }
}
