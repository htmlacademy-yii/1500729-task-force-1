<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property string|null $content
 * @property int $task_id
 * @property int $ratio
 *
 * @property Tasks $task
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add'], 'safe'],
            [['task_id', 'ratio'], 'required'],
            [['ratio'], 'integer', 'min' => 1, 'max' => 5],
            [['task_id'], 'integer'],
            [['content'], 'string', 'max' => 256],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['task_id'], 'validateUser']
        ];
    }

    public function validateUser($attribute, $params) {
        if ($this->task->author_id !== Yii::$app->user->id) {
            $this->addError($attribute, 'Завершить задание может только его автор');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'content' => 'Content',
            'task_id' => 'Task ID',
            'ratio' => 'Ratio',
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
}
