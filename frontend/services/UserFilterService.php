<?php

namespace frontend\services;

use frontend\models\FilterUsers;
use frontend\models\Users;
use yii\helpers\ArrayHelper;

class UserFilterService
{
      public function filterUsers($users, array $data, FilterUsers $model) {
          try {
              $model->load($data);
              if ($model->search) {
                  $model->options = NULL;
                  $model->category_id = NULL;
                  $users = $users->andFilterWhere($model->getSearch());
              }
              if ($model->options && ArrayHelper::isIn(1,$model->options)) {
                  $users = $users->andFilterWhere($model->getFreeExecutors());
              }
              if ($model->options && ArrayHelper::isIn(2,$model->options)) {
                  $users = $users->andFilterWhere($model->getOnlineUsers());
              }
              if ($model->options && ArrayHelper::isIn(3,$model->options)) {
                  $users = $users->andFilterWhere($model->getUsersWithReviews());
              }
              if ($model->options && ArrayHelper::isIn(4,$model->options)) {
                  $users = $users->andFilterWhere($model->getFavouriteUsers());
              }
              if ($model->category_id) {
                  $users = $users->joinWith('executorCategories', 'true', 'INNER JOIN')
                      ->andFilterWhere($model->filterCategories());
              }
              return $users;

          } catch (\Exception $e) {
              throw $e;
          }
      }
}
