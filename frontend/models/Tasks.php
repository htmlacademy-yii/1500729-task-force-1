<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property int $author_id
 * @property int|null $city_id
 * @property string|null $address
 * @property int|null $location_id
 * @property int|null $budget
 * @property string|null $due_date
 * @property int|null $status
 * @property int|null $executor_id
 * @property string $location0
 * @property float|null $latitude
 * @property float|null $longitude
 *
 * @property Messages[] $messages
 * @property Responds[] $responds
 * @property Reviews[] $reviews
 * @property TaskFiles[] $taskFiles
 * @property Users $author
 * @property Users $executor
 * @property Locations $city
 * @property Categories $category
 * @property Locations $location
 * @property Notifications[] $notifications
 */
class Tasks extends \yii\db\ActiveRecord
{
    public const STATUS_NEW = 0;
    public const STATUS_CANCEL = 1;
    public const STATUS_IN_WORK = 2;
    public const STATUS_DONE = 3;
    public const STATUS_FAILED = 4;
    public const STATUS_OVERDUE = 5;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add', 'due_date'], 'safe'],
            [['title', 'description', 'category_id', 'author_id'], 'required'],
            [['category_id', 'author_id', 'city_id', 'location_id', 'budget', 'status', 'executor_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 256],
            [['address'], 'string', 'max' => 64],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['author_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::class, 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::class, 'targetAttribute' => ['location_id' => 'id']],

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
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'author_id' => 'Author ID',
            'city_id' => 'City ID',
            'address' => 'Address',
            'location_id' => 'Location ID',
            'budget' => 'Budget',
            'due_date' => 'Due Date',
            'status' => 'Status',
            'executor_id' => 'Executor ID',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude'
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
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

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Locations::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
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

    public function getNotifications()
    {
        return $this->hasMany(Notifications::class, ['task_id' => 'id']);
    }

    public function getActions(int $user_id, ?object $respond_id)
    {
        $activeActions = [];


        if ($this->executor_id !== $user_id && $user_id !== $this->author_id && $this->status === self::STATUS_NEW  && !$respond_id) {
            $activeActions[] = ['response' => 'Откликнуться'];
        }
        if ($user_id === $this->author_id && $this->status === self::STATUS_NEW) {
            $activeActions[] = ['action_cancel' => 'Отменить задачу'];
        }
        if ($user_id === $this->author_id && $this->status === self::STATUS_IN_WORK) {
            $activeActions[] = ['request' => 'Завершить'];
        }
        if ($user_id === $this->executor_id && $this->status === self::STATUS_IN_WORK) {
            $activeActions[] = ['refusal' => 'Отказаться'];
        }

        return $activeActions;
    }
    public function validateCancel(): bool
    {
        if ($this->author_id === Yii::$app->user->id && $this->status === self::STATUS_NEW) {
            return true;
        } else {
            return false;
        }
    }

    public function getStatus(): array
    {
        $data = [
            self::STATUS_NEW => [
                'new' => 'Новый'
            ],
            self::STATUS_DONE => [
                'done' => 'Завершено'
            ],
            self::STATUS_IN_WORK => [
                'work' => 'В работе'
            ],
            self::STATUS_FAILED => [
                'failed' => 'Отменен'
                ],
            self::STATUS_CANCEL => [
                'canсel' => 'Отменен'
            ]
        ];
        return $data[$this->status];
    }
}
