<?php

namespace frontend\services;

use frontend\models\Files;
use frontend\models\TaskFiles;
use frontend\models\TaskForm;
use Yii;

class TaskCreateService
{
    public function execute(TaskForm $task): int
    {

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $task_id = $task->createTask();
            if (Yii::$app->session->get('files')) {
                foreach (Yii::$app->session->get('files') as $name => $file) {
                    $file_id = $this->createFiles($file, $name);
                    $this->createTaskFiles($file_id, $task_id)->save();
                }
                Yii::$app->session->remove('files');
            }
            $transaction->commit();
            return $task_id;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

    }

    private function createFiles($file, $name): int
    {
        $file_path = new Files();
        $file_path->path = '/uploads/' . $file;
        $file_path->name = $name;
        $file_path->save();
        return $file_path->id;
    }

    private function createTaskFiles($file_id, $task_id): TaskFiles
    {
        $task_file = new TaskFiles();
        $task_file->file_id = $file_id;
        $task_file->task_id = $task_id;
        return $task_file;
    }

}
