<?php

namespace frontend\services;

use frontend\models\Tasks;

class MyTasksService
{
     public function filterTasks($data, $tasks) {
         try {
             if ($data['status']) {
                 return $tasks->andFilterWhere(['status' => $data['status']]);
             }
         } catch (\Exception $e) {
             throw $e;
         }
     }
}
