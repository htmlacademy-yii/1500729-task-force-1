<?php

namespace frontend\services;

use frontend\models\FilterTasks;
use frontend\models\Tasks;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class TaskFilterService
{
    public function filterTasks(object $task, array $data, FilterTasks $model)
    {

        try {
            $model->load($data);
            if ($model->category_id) {
                return $task->andFilterWhere(['IN', 'category_id', $model->category_id]);
            }
            if ($model->search) {
                $model->options = NULL;
                $model->category_id = NULL;
                $model->period = NULL;
                return $task->andFilterWhere(['LIKE', 'title', $model->search]);
            }

            if ($model->options && ArrayHelper::isIn(1, $model->options)) {
                return $task->andFilterWhere($model->getTasksWithoutResponds());
            }
            if ($model->options && ArrayHelper::isIn(2, $model->options)) {
                return $task->andWhere($model->getRemoteTasks());
            }
            if ($model->period) {
                return $task->andFilterWhere($model->getPeriod());
            }
            return $task;

        } catch (\Exception $e) {
            throw $e;
        }
    }

}
