<?php

namespace frontend\models;

use yii\base\Model;

class RefuseTaskForm extends Model
{
    public $user_id;
    public $status;
    public $executor_id;

    public function rules()
    {
        return [['user_id', 'validateUser']];
    }

    public function validateUser($attribute, $params) {
        if ($this->status !== Tasks::STATUS_IN_WORK && $attribute !== $this->executor_id) {
            $this->addError($attribute, 'Вы не можете отказаться от этого задания');
        }
    }
}
