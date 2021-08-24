<?php


namespace frontend\models;


use Cassandra\Date;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class FilterTasks extends Model
{
    public $category_id;
    public $options;
    public $period;
    public $search;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [['category_id', 'safe'],
            ['options', 'safe'],
            ['period', 'safe'],
            ['search', 'safe']];
    }

    public function getOptions() {
        return [
            1 => 'Без исполнителя',
            2 => 'Удаленная работа'
        ];
    }

    public function getDataTimes() {
        return [
            'today' => 'За день',
            'week' => 'За неделю',
            'month' => 'За месяц'
        ];
    }

    public function getTasksWithoutResponds() {
        $null = new Expression('NULL');
        return ['IS', 'task_id', $null];
    }

    public function getRemoteTasks()
    {
        $null = new Expression('NULL');
        return ['IN', 'location_id', $null];
    }

    public function getPeriod() {
        if ($this->period == 'today') {
            $time = new \DateTime('today');
        }
        if ($this->period == 'week') {
            $time = new \DateTime('-1 week');
        }
        if ($this->period == 'month') {
            $time = new \DateTime('-1 month');
        }
        $time = $time->format('Y-m-d H:i:s');
        return ['>=', 'tasks.dt_add', $time];
    }

}
