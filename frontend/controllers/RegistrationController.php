<?php


namespace frontend\controllers;


use frontend\models\Registration;
use http\Client\Response;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class RegistrationController extends Controller
{
    public function behaviors()
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
    public function actionIndex()
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
