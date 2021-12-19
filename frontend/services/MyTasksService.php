<?php

namespace frontend\services;

use Exception;
use frontend\models\Tasks;

class MyTasksService
{
    /**
     * @param array $data
     * @param $tasks
     * @return mixed|void
     * @throws Exception
     */
    public function filterTasks(array $data, $tasks)
    {
        try {
            if ($data['status']) {
                return $tasks->andFilterWhere(['status' => $data['status']]);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
