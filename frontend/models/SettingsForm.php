<?php

namespace frontend\models;

class SettingsForm extends \yii\base\Model
{
    public $avatar;
    public $name;
    public $email;
    public $location_id;
    public $birthday;
    public $information;
    public $category_id;
    public $role;
    public $password;
    public $repeat_password;
    public $photos;
    public $phone;
    public $skype;
    public $other_contact;
    public $notice_new_message;
    public $notice_new_action;
    public $notice_new_review;
    public $show_contacts;
    public $show_profile;




    public function rules()
    {
        return [
              [['name'], 'required'],
              ['name', 'string'],
              ['email', 'email'],
              ['email', 'required'],
              ['location_id', 'safe'],
              ['birthday', 'date', 'format' => 'php: Y-m-d'],
              ['information', 'string'],
              ['category_id', 'safe'],
              ['password', 'string', 'min' => 8, 'message' => 'Пароль должен быть не менее 8 символов'],
              ['repeat_password', 'validatePassword'],
              ['repeat_password', 'compare', 'compareAttribute' => 'password', 'message' => "Пароли не совпадают"],
              ['password', 'compare', 'compareAttribute' => 'repeat_password', 'message' => "Введите повтор пароля"],
              ['phone', 'string', 'min' => 11, 'message' => 'Телефон должен состоять из 11 цифр'],
              ['skype', 'string', 'min' => 3, 'message' => 'Введите не менее 3 знаков'],
              ['other_contact', 'string'],
              ['notice_new_message', 'safe'],
              ['notice_new_action', 'safe'],
              ['notice_new_review', 'safe'],
              ['show_contacts', 'safe'],
              ['show_profile', 'safe'],
              [['avatar'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif']],
          ];
    }

    public function validatePassword($attribute, $params)
    {
        if ($this->password && !$attribute) {
            $this->addError($attribute, "Введите пароль повторно");
        }
    }
}
