<?php

namespace frontend\models;

use yii\base\Model;

class ChooseExecutorForm extends Model
{
     public $user_id;
     public $status;
     public $author_id;

     public function rules()
     {
          return [['user_id', 'validateUser']];
     }

     public function validateUser($attribute, $params) {
          if ($this->status !== Tasks::STATUS_NEW && $attribute !== $this->author_id) {
              $this->addError($attribute, 'Вы не можете назначить исполнителя к этому заданию');
          }
     }
}
