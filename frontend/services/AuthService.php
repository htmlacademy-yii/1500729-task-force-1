<?php

namespace frontend\services;

use frontend\models\Auth;
use frontend\models\Files;
use frontend\models\Locations;
use frontend\models\Users;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class AuthService
{
    public $attributes;
    public $client;

    public function __construct($attributes, $client)
    {
        $this->attributes = $attributes;
        $this->client = $client;
    }

    /**
     * @return Users
     * @throws Exception|\yii\base\Exception
     */
    public function execute(): Users
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $newUser = $this->newUser();
            $newUser->avatar_id = $this->getAvatar();
            if (!$newUser->save()) {
                throw new Exception('Ошибка сохранения пользователя', 500);
            } else {
                $this->newAuthUser($newUser->id);
            }
            $transaction->commit();
            return $newUser;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return Users
     * @throws \yii\base\Exception
     */
    private function newUser(): Users
    {
        $user = new Users();
        $user->name = $this->attributes['first_name'];
        $user->email = $this->attributes['email'];
        $user->password = Yii::$app->security->generateRandomString(8);
        $birthday = date('Y-m-d', strtotime($this->attributes['bdate']));
        $user->birthday = $birthday;

        $location = Locations::find()->where(['location' => $this->attributes['city']['title']])->one();

        if ($location) {
            $user->location_id = $location->id;
        } else {
            $location = new Locations();
            $location->location = $this->attributes['city']['title'];
            $location->save();
            $user->location_id = $location->id;
        }
        return $user;
    }

    /**
     * @return int|void
     * @throws Exception
     * @throws \Exception
     */
    private function getAvatar()
    {
        $vk_data_response = $this->client->api('users.get', 'POST', ['uids' => $this->attributes['id'], 'fields' => 'photo_max']);
        if ($vk_data_response = ArrayHelper::getValue($vk_data_response, 'response', false)) {
            $vk_data = array_shift($vk_data_response);
            $avatar = new Files();
            $avatar->path = $vk_data['photo_max'];
            if (!$avatar->save()) {
                throw new Exception('Ошибка при сохранении аватара', 500);
            } else {
                return $avatar->id;
            }
        }
    }

    /**
     * @param int $user_id
     * @throws Exception
     */
    private function newAuthUser(int $user_id)
    {
        $auth = new Auth([
            'user_id' => $user_id,
            'source' => $this->client->getId(),
            'source_id' => (string)$this->attributes['id'],
        ]);
        if (!$auth->save()) {
            throw new Exception('Ошибка при сохранении пользователя Auth', 500);
        }
    }
}
