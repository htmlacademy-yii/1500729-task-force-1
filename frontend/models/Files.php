<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $path
 * @property string $name;
 *
 * @property TaskFiles[] $taskFiles
 * @property Users[] $users
 * @property WorkPhotos[] $workPhotos
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 500],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'name' => 'Name'
        ];
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::class, ['file_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['avatar_id' => 'id']);
    }

    /**
     * Gets query for [[WorkPhotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkPhotos()
    {
        return $this->hasMany(WorkPhotos::class, ['file_id' => 'id']);
    }
}
