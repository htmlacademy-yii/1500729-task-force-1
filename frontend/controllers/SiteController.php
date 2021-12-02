<?php

namespace frontend\controllers;

/** @var $client VKontakte */

use common\models\User;
use frontend\models\Auth;
use frontend\models\Files;
use frontend\models\Locations;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\Users;
use frontend\models\VerifyEmailForm;
use frontend\services\AuthService;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ]
        ];
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        $auth = Auth::find()->where(['source' => $client->getId(),
            'source_id' => $attributes['id']])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) {
                $user = $auth->user;
                Yii::$app->user->login($user);
                Yii::$app->session->set('location_id', Yii::$app->user->getIdentity()->location_id);
                return $this->redirect(['tasks/index']);
            } else {
                if (isset($attributes['email']) && Users::find()->where(['email' => $attributes['email']])->exists()) {
                    throw new \yii\base\Exception('Пользователь с таким Email уже зарегистрирован');
                } else {
                    $user = (new AuthService($attributes, $client))->execute();
                    Yii::$app->user->login($user);
                    Yii::$app->session->set('location_id', Yii::$app->user->getIdentity()->location_id);
                    return $this->redirect(['tasks/index']);
                }

            }

        }
    }



    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['tasks/index']);
        }
        $this->layout = false;
        $loginForm = new \frontend\models\LoginForm();
        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);

            }
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                \Yii::$app->user->login($user);
                Yii::$app->session->set('location_id', Yii::$app->user->getIdentity()->location_id);
                return $this->redirect(['tasks/index']);
            }
        }
        return $this->render('landing', ['loginForm' => $loginForm]);
    }



    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
