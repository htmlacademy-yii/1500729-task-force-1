<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "responds".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property int $budget
 * @property string|null $content
 * @property int $task_id
 * @property int $executor_id
 * @property int $decline
 *
 * @property Tasks $task
 * @property Users $executor
 */
class Responds extends \yii\db\ActiveRecord
{
    /**
     * @var mixed|null
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add'], 'safe'],
            [['budget', 'task_id', 'executor_id'], 'required'],
            [['budget', 'task_id', 'executor_id'], 'integer'],
            [['content'], 'string', 'max' => 256],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['decline'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'budget' => 'Budget',
            'content' => 'Content',
            'task_id' => 'Task ID',
            'executor_id' => 'Executor ID',
            'decline' => 'Decline'
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'executor_id']);
    }
}
