<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

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
 * @property Tasks[] $ownTasks
 * @property Tasks[] $executeTasks
 * @property Locations $location
 * @property Files $avatar
 * @property WorkPhotos[] $workPhotos
 * @property Favourites[] $ownFavourites
 * @property Favourites[] $executeFavourites
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public const ROLE_EXECUTOR = 1;
    public const ROLE_AUTHOR = 0;
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
     * Gets query for [[OwnTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwnTasks()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[ExecuteTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecuteTasks()
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

    public static function calculateStars($id)
    {
        $userTasksQuery = (new Query())->select('id')->from('tasks')->where('executor_id = :executor_id', [':executor_id' => $id]);
        $query = new Query();
        $query->select(['AVG(ratio)'])->from('reviews')->
        where(['task_id' => $userTasksQuery]);
        $stars = $query->one();
        if ($stars['AVG(ratio)']) {
            return $stars['AVG(ratio)'];
        } else {
            return 0;
        }
    }

    /**
     * Gets query for [[Favourites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwnFavourites()
    {
        return $this->hasMany(Favourites::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Favourites0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecuteFavourites()
    {
        return $this->hasMany(Favourites::class, ['executor_id' => 'id']);
    }

    public function getAuthorCountTasks()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id'])->count();
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
