<?php

namespace frontend\services;

use frontend\models\RefuseTaskForm;
use frontend\models\Tasks;
use frontend\models\Users;
use Yii;

class TaskRefuseService
{
      public function execute(Tasks $task, int $executorId ) {
          $db = Yii::$app->db;
          $transaction = $db->beginTransaction();
          try {
              $this->updateTask($task);
              $this->updateExecutor($executorId);
              $transaction->commit();
          } catch (\Exception $e) {
              $transaction->rollBack();
              throw $e;
          }

      }

      private function updateTask(Tasks $task) {
          $task->status = Tasks::STATUS_FAILED;
          $task->save();
      }

      private function updateExecutor(int $executorId) {
          $executor = $executorId = Users::findOne($executorId);
          if ($executor->failed_tasks) {
              $executor->updateCounters(['failed_tasks' => 1]);
          } else {
              $executor->failed_tasks = 1;
              $executor->save();
          }
      }
}
