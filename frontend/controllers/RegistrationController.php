<?php

namespace frontend\controllers;

use frontend\models\Registration;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $model = new Registration();
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->signUp();
                $this->goHome();
            }
        }
        return $this->render('index', ['model' => $model]);
    }
}
