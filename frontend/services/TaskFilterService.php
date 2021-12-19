<?php

namespace frontend\services;

use Exception;
use frontend\models\FilterTasks;
use yii\helpers\ArrayHelper;

class TaskFilterService
{
    /**
     * @param object $task
     * @param array $data
     * @param FilterTasks $model
     * @return object
     * @throws Exception
     */
    public function filterTasks(object $task, array $data, FilterTasks $model): object
    {
        try {
            $model->load($data);
            if ($model->category_id) {
                return $task->andFilterWhere(['IN', 'category_id', $model->category_id]);
            }
            if ($model->search) {
                $model->options = null;
                $model->category_id = null;
                $model->period = null;
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
        } catch (Exception $e) {
            throw $e;
        }
    }
}
