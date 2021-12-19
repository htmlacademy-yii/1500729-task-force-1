<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\SettingsForm;
use frontend\models\Users;
use frontend\services\SettingsFormService;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

class SettingsController extends SecuredController
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($this->action->id == 'index') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex(): string
    {
        $categories = Categories::find()->all();
        $user_id = Yii::$app->user->id;
        $settings = new SettingsForm();
        $user = Users::findOne($user_id);
        $settings->setAttributes($user->attributes);
        $settings->password = null;

        if ($files = UploadedFile::getInstancesByName('file')) {
            SettingsFormService::savePhotos($files);
        }

        if (Yii::$app->request->post()) {
            $settings->load(Yii::$app->request->post());
            (new SettingsFormService())->execute($settings, $user, $categories);
            $this->redirect(['settings/index']);
        }
        return $this->render('index', ['model' => $settings, 'categories' => $categories, 'user_id' => $user_id, 'user' => $user]);
    }
}
