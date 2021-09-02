<?php


namespace frontend\models;


use yii\base\Model;
use frontend\models\Users;
use yii\helpers\ArrayHelper;

class Registration extends Model
{
     public $email;
     public $name;
     public $location;
     public $password;

     public function rules()
     {
         return [

             ['email', 'required'],
             ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
             [['email'], 'unique', 'targetClass' => 'frontend\models\Users', 'message' => 'Такая электронная почта уже зарегистрирована'],

             ['name', 'required'],

             ['location', 'required'],

             ['password', 'required'],
             ['password', 'string', 'min' => 8]
         ];
     }

     public function getLocations() {
         $locations = Locations::find()->all();
         return ArrayHelper::map($locations, 'id', 'location');
     }
}
