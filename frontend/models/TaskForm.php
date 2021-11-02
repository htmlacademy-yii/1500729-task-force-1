<?php


namespace frontend\models;


use yii\base\Model;

class TaskForm extends Model
{
    public $title;
    public $description;
    public $category_id;
    public $files;
    public $budget;
    public $due_date;
    public $address;
    public $coordinates;
    public $location;

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
            ['address', 'string'],
            ['coordinates', 'string'],
            ['address', 'validateValue'],
            ['location', 'string']
        ];
    }

    public function createTask()
    {
        $author = \Yii::$app->user->identity;
        $task = new Tasks();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category_id;
        $task->budget = $this->budget;
        $task->author_id = $author->getId();
        $task->due_date = $this->due_date;
        $task->address = $this->address;

        if ($this->coordinates) {
            $format_coordinates = explode(" ", $this->coordinates);
            $task->latitude = (float)$format_coordinates[0];
            $task->longitude = (float)$format_coordinates[1];
            $task->location_id = $author->location_id;
        }
        $task->save();
        return $task->id;
    }
    public function validateValue($attribute, $params) {
        if (!$this->coordinates && $attribute) {
            $this->addError($attribute, "Пожалуйста, выберите адрес из выпадающего списка");
        }
    }

}
