<?php

namespace frontend\services;

use frontend\models\ExecutorCategories;
use frontend\models\Files;
use frontend\models\SettingsForm;
use frontend\models\Users;
use frontend\models\WorkPhotos;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class SettingsFormService
{
    /**
     * @param SettingsForm $settings
     * @param Users $user
     * @param array $categories
     * @throws Exception
     */
    public function execute(SettingsForm $settings, Users $user, array $categories): void
    {
        $executor_categories = ExecutorCategories::find()->where(['user_id' => $user->id])->asArray()->all();
        $settings->avatar = UploadedFile::getInstance($settings, 'avatar');
        if ($settings->validate()) {
            try {
                $db = Yii::$app->db;
                $transaction = $db->beginTransaction();
                $this->uploadSettings($user, $settings);

                if (Yii::$app->session->get('photos')) {
                    $this->uploadFiles($user);
                }

                if ($settings->category_id) {
                    $this->updateCategory($user, $settings, $executor_categories, $categories);
                } else {
                    $this->changeRole($user, $executor_categories);
                }

                if ($settings->avatar) {
                    $user->avatar_id = $this->uploadAvatar($settings->avatar);
                }

                $user->save();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    private function uploadFiles(Users $user)
    {
        $files = Yii::$app->session->get('photos');
        $old_photos = $user->workPhotos;
        if ($old_photos) {
            foreach ($old_photos as $old_photo) {
                $old_photo->delete();
                $old_photo->file->delete();
            }
        }

        foreach ($files as $path) {
            $file = new Files();
            $file->path = $path;
            $file->save();
            $photo = new WorkPhotos();
            $photo->user_id = $user->id;
            $photo->file_id = $file->id;
            $photo->save();
        }

        Yii::$app->session->remove('photos');
    }

    /**
     * @param Users $user
     * @param SettingsForm $settings
     * @throws \yii\base\Exception
     */
    private function uploadSettings(Users $user, SettingsForm $settings)
    {
        $user->email = $settings->email;
        $user->birthday = $settings->birthday;
        if ($settings->location_id) {
            $user->location_id = $settings->location_id;
            Yii::$app->session->set('location_id', $user->location_id);
        }
        $user->information = $settings->information;
        $user->phone = $settings->phone;
        $user->skype = $settings->skype;
        $user->other_contact = $settings->other_contact;
        $user->notice_new_message = $settings->notice_new_message;
        $user->notice_new_action = $settings->notice_new_action;
        $user->notice_new_review = $settings->notice_new_review;
        $user->show_profile = $settings->show_profile;
        $user->show_contacts = $settings->show_contacts;

        if ($settings->password) {
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($settings->password);
        }
    }


    /**
     * @param $avatar
     * @return int
     */
    private function uploadAvatar($avatar): int
    {
        $filename = uniqid('ava') . '.' . $avatar->getExtension();
        $avatar->saveAs('@webroot/uploads/' . $filename);
        $file = new Files();
        $file->path = '/uploads/' . $filename;
        $file->save();
        return $file->id;
    }

    /**
     * @param Users $user
     * @param SettingsForm $settings
     * @param $executor_categories
     * @param $categories
     * @throws StaleObjectException
     * @throws Throwable
     */
    private function updateCategory(Users $user, SettingsForm $settings, $executor_categories, $categories)
    {
        $user->role = Users::ROLE_EXECUTOR;
        foreach ($settings->category_id as $category) {
            if (!ArrayHelper::isIn($category, ArrayHelper::map($executor_categories, 'id', 'category_id'))) {
                $new_executor_category = new ExecutorCategories();
                $new_executor_category->user_id = $user->id;
                $new_executor_category->category_id = $category;
                $new_executor_category->save();
            }
        }
        foreach ($categories as $_category) {
            if (!ArrayHelper::isIn($_category->id, $settings->category_id) &&
                ArrayHelper::isIn($_category->id, ArrayHelper::map($executor_categories, 'id', 'category_id'))) {
                $category_delete = ExecutorCategories::find()->where(['category_id' => $_category->id,
                    'user_id' => $user->id])->one()->delete();
            }
        }
    }

    /**
     * @param Users $user
     * @param $executor_categories
     * @throws StaleObjectException
     * @throws Throwable
     */
    private function changeRole(Users $user, $executor_categories)
    {
        $user->role = Users::ROLE_AUTHOR;
        if ($executor_categories) {
            $categories_for_delete = ExecutorCategories::find()->where(['user_id' => $user->id])->all();
            foreach ($categories_for_delete as $item) {
                $item->delete();
            }
        }
    }

    public static function savePhotos($files)
    {
        $files_path = [];
        foreach ($files as $file) {
            $filename = uniqid('photo') . '.' . $file->getExtension();
            $file->saveAs('@webroot/uploads/' . $filename);
            $files_path[] = '/uploads/' . $filename;
        }
        if (Yii::$app->session->has('photos')) {
            $photos = Yii::$app->session->get('photos');
            foreach ($files_path as $file) {
                $photos[] = $file;
            }
            Yii::$app->session->set('photos', $photos);
        } else {
            Yii::$app->session->set('photos', $files_path);
        }
    }
}
