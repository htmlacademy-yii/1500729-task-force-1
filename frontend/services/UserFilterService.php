<?php

namespace frontend\services;

use Exception;
use frontend\models\FilterUsers;
use yii\helpers\ArrayHelper;

class UserFilterService
{
    /**
     * @param $users
     * @param array $data
     * @param FilterUsers $model
     * @return mixed
     * @throws Exception
     */
    public function filterUsers($users, array $data, FilterUsers $model)
    {
        try {
            $model->load($data);
            if ($model->search) {
                $model->options = null;
                $model->category_id = null;
                $users = $users->andFilterWhere($model->getSearch());
            }
            if ($model->options && ArrayHelper::isIn(1, $model->options)) {
                $users = $users->andFilterWhere($model->getFreeExecutors());
            }
            if ($model->options && ArrayHelper::isIn(2, $model->options)) {
                $users = $users->andFilterWhere($model->getOnlineUsers());
            }
            if ($model->options && ArrayHelper::isIn(3, $model->options)) {
                $users = $users->andFilterWhere($model->getUsersWithReviews());
            }
            if ($model->options && ArrayHelper::isIn(4, $model->options)) {
                $users = $users->andFilterWhere($model->getFavouriteUsers());
            }
            if ($model->category_id) {
                $users = $users->joinWith('executorCategories', 'true', 'INNER JOIN')
                      ->andFilterWhere($model->filterCategories());
            }
            return $users;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
