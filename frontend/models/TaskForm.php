<?php


namespace frontend\models;


use yii\base\Model;

class TaskForm extends Model {
    public $title;
    public $description;
    public $category_id;
    public $files;
    public $budget;
    public $due_date;

    public function rules()
    {
        return [
        ['title', 'required'],
        ['description', 'required'],
        ['category_id', 'required'],
            [['budget'], 'integer', 'message' => 'Бюджет должен быть целым числом'],
            ['due_date', 'date', 'format' => 'YYYY-MM-DD', 'message' => 'Дата исполнения должна быть в формате ГГГГ-ММ-ДД'],
            ['title', 'string'],
            ['description', 'string'],



    ];
    }

    public function createTask() {
        $task = new Tasks();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category_id;
        $task->budget = $this->budget;
        $task->author_id = \Yii::$app->user->identity->getId();
        $task->due_date = $this->due_date;
        $task->save();
        return $task->id;
    }

}
