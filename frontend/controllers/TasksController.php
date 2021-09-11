<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\FilterTasks;
use frontend\models\FilterUsers;
use frontend\models\Responds;
use frontend\models\Tasks;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller as ControllerAlias;
use yii\web\NotFoundHttpException;


class TasksController extends ControllerAlias
{
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $model = new FilterTasks();
        $model->load($get);
        $categories = Categories::find()->all();

        $tasks = Tasks::find()->where(['status' => Tasks::STATUS_NEW])
            ->orderBy(['dt_add' => SORT_DESC])
            ->with('category')
            ->with('location')->joinWith('responds');
        if ($model->load(Yii::$app->request->get())) {
            if ($model->category_id) {
                $tasks = $tasks->andFilterWhere(['IN', 'category_id', $model->category_id]);
            }
            if ($model->search) {
                $model->options = NULL;
                $model->category_id = NULL;
                $model->period = NULL;
                $tasks = $tasks->andFilterWhere(['LIKE', 'title', $model->search]);
            }

            if ($model->options && ArrayHelper::isIn(1,$model->options)) {
                $tasks = $tasks->andFilterWhere($model->getTasksWithoutResponds());
            }
            if ($model->options && ArrayHelper::isIn(2,$model->options)) {
                $tasks = $tasks->andFilterWhere($model->getRemoteTasks());
            }
            if ($model->period) {
                $tasks = $tasks->andFilterWhere($model->getPeriod());
            }
        }

        $tasks = $tasks->all();
        return $this->render('tasks', ['tasks' => $tasks, 'model' => $model, 'categories' => $categories]);
    }

    public function actionView($id) {
        $task = Tasks::find()->where(['id' => $id])->with('category')
            ->with('taskFiles')->with('location')->with('author')->one();
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID {$id} не найдено");
        }

        return $this->render('view', ['task' => $task]);
    }

}
