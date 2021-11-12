<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property string $type
 * @property int $task_id
 * @property int $recipient_id
 * @property string|null $date_add
 * @property int|null $read
 *
 * @property Users $recipient
 * @property Tasks $task
 */
class Notifications extends \yii\db\ActiveRecord
{
    const UNREAD = 0;
    const READ = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'task_id', 'recipient_id'], 'required'],
            [['task_id', 'recipient_id', 'read'], 'integer'],
            [['date_add'], 'safe'],
            [['type'], 'string', 'max' => 255],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['recipient_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'task_id' => 'Task ID',
            'recipient_id' => 'Recipient ID',
            'date_add' => 'Date Add',
            'read' => 'Read',
        ];
    }

    /**
     * Gets query for [[Recipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(Users::class, ['id' => 'recipient_id']);
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

    public function getTypes() {
        return [
          'message' => 'Новое сообщение в чате ' . $this->task->title,
            'executor' => 'Выбран исполнитель для ' . $this->task->title,
            'close' => 'Завершено задание ' . $this->task->title,
            'respond' => 'Новый отклик к заданию ' . $this->task->title,
            'refuse' => 'Исполнитель отказался от выполнения задания ' . $this->task->title
        ];
    }
}
