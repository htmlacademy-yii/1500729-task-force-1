<?php


namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


class FilterUsers extends Model
{
    public $category_id;
    public $options;
    public $search;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [['category_id', 'safe'],
            ['options', 'safe'],
            ['search', 'safe']];
    }

    public function getOptions()
    {
        return [
            1 => "Сейчас свободен",
            2 => "Сейчас онлайн",
            3 => "Есть отзывы",
            4 => "В избранном"
        ];
    }


    public function filterCategories()
    {
            return (['IN', 'executor_categories.category_id', $this->category_id]);
    }

    public function getOnlineUsers()
    {
        $now = new \DateTime('-30 minutes');
        $now = $now->format('Y-m-d H:i:s');
        return ['>=', 'dt_last_activity', $now];

    }

    public function getUsersWithReviews()
    {
        $users = Tasks::find()->select('executor_id')->joinWith('reviews', 'true', 'INNER JOIN')->asArray();
        return ['IN', 'users.id', $users];
    }

    public function getFavouriteUsers()
    {
        $favouritesUsers = Favourites::find()->select('executor_id')->where(['author_id' => Yii::$app->user->id])->asArray();
        return ['IN', 'users.id', $favouritesUsers];
    }

    public function getSearch()
    {
        return ['LIKE', 'name', $this->search];
    }

    public function getFreeExecutors()
    {
        $null = new Expression('NULL');
        return ['IS', 'executor_id', $null];
    }

}


