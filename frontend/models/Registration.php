<?php

namespace frontend\models;

use yii\base\Model;
use frontend\models\Users;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class Registration extends Model
{
    public $email;
    public $name;
    public $location;
    public $password;

    public function rules()
    {
        return [

             ['email', 'required', 'message' => 'Введите Ваш электронный адрес'],
             ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
             [['email'], 'unique', 'targetClass' => 'frontend\models\Users', 'message' => 'Такая электронная почта уже зарегистрирована'],

             ['name', 'required', 'message' => 'Введите Ваше имя'],

             ['location', 'required','message' => 'Выберите Ваш город'],

             ['password', 'required', 'message' => 'Укажите пароль'],
             ['password', 'string', 'min' => 8, 'message' => 'Пароль должен быть не менее 8 символов']
         ];
    }

    public static function getLocations()
    {
        $locations = Locations::find()->all();
        return ArrayHelper::map($locations, 'id', 'location');
    }

    public function signUp()
    {
        $user = new Users();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->location_id = $this->location;
        $user->password = \Yii::$app->getSecurity()->generatePasswordHash($this->password);

        return $user->save();
    }
}
