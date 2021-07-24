<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property string $email
 * @property string $name
 * @property int|null $avatar_id
 * @property string|null $information
 * @property string|null $birthday
 * @property string|null $address
 * @property int|null $location_id
 * @property string $password
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $telegram
 * @property string|null $other_contact
 * @property string|null $dt_last_activity
 * @property int|null $show_profile
 * @property int|null $show_contacts
 * @property int|null $notice_new_message
 * @property int|null $notice_new_action
 * @property int|null $notice_new_review
 * @property int|null $failed_tasks
 * @property int|null $done_tasks
 * @property int|null $role
 *
 * @property ExecutorCategories[] $executorCategories
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Responds[] $responds
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 * @property Locations $location
 * @property Files $avatar
 * @property WorkPhotos[] $workPhotos
 */
class Users extends \yii\db\ActiveRecord
{
    const ROLE_EXECUTOR = 1;
    const ROLE_AUTHOR = 0;
        /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add', 'birthday', 'dt_last_activity'], 'safe'],
            [['email', 'name', 'password'], 'required'],
            [['avatar_id', 'location_id', 'show_profile', 'show_contacts', 'notice_new_message', 'notice_new_action', 'notice_new_review', 'failed_tasks', 'done_tasks', 'role'], 'integer'],
            [['information'], 'string'],
            [['email', 'name', 'address'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 60],
            [['phone', 'skype', 'telegram', 'other_contact'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::class, 'targetAttribute' => ['location_id' => 'id']],
            [['avatar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['avatar_id' => 'id']],
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
            'email' => 'Email',
            'name' => 'Name',
            'avatar_id' => 'Avatar ID',
            'information' => 'Information',
            'birthday' => 'Birthday',
            'address' => 'Address',
            'location_id' => 'Location ID',
            'password' => 'Password',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'other_contact' => 'Other Contact',
            'dt_last_activity' => 'Dt Last Activity',
            'show_profile' => 'Show Profile',
            'show_contacts' => 'Show Contacts',
            'notice_new_message' => 'Notice New Message',
            'notice_new_action' => 'Notice New Action',
            'notice_new_review' => 'Notice New Review',
            'failed_tasks' => 'Failed Tasks',
            'done_tasks' => 'Done Tasks',
            'role' => 'Role',
        ];
    }

    /**
     * Gets query for [[ExecutorCategoriesFixture]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorCategories()
    {
        return $this->hasMany(ExecutorCategories::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Messages::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Tasks::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Location]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Locations::class, ['id' => 'location_id']);
    }

    /**
     * Gets query for [[Avatar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatar()
    {

        return $this->hasOne(Files::class, ['id' => 'avatar_id']);
    }

    /**
     * Gets query for [[WorkPhotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkPhotos()
    {
        return $this->hasMany(WorkPhotos::class, ['user_id' => 'id']);
    }
}
