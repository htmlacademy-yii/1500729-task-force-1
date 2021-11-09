<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $content
 * @property string|null $dt_add
 * @property int $task_id
 * @property int $sender_id
 * @property int $recipient_id
 * @property int|null $message_read
 * @property int|null $is_mine
 *
 * @property Tasks $task
 * @property Users $sender
 * @property Users $recipient
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $is_mine;
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'task_id', 'sender_id', 'recipient_id'], 'required'],
            [['content'], 'string'],
            [['dt_add', 'is_mine'], 'safe'],
            [['task_id', 'sender_id', 'recipient_id', 'message_read', 'is_mine'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['sender_id' => 'id']],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'dt_add' => 'Dt Add',
            'task_id' => 'Task ID',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
            'message_read' => 'Message Read',

        ];
    }

    public function fields()
    {
        $field = parent::fields();
        $field[] = 'is_mine';
        return $field;
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
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Users::class, ['id' => 'sender_id']);
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
}
