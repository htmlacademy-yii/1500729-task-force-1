<?php

namespace frontend\controllers;

/** @var $client VKontakte */

use frontend\models\Auth;
use frontend\models\LoginForm;
use frontend\models\Tasks;
use frontend\models\Users;
use frontend\services\AuthService;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\web\Controller;
use yii\filters\AccessControl;
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
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'auth'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index']
                    ]
                ]
            ]
        ];
    }


    public function actions(): array
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

    public function onAuthSuccess($client): Response
    {
        $attributes = $client->getUserAttributes();

        $auth = Auth::find()->where(['source' => $client->getId(),
            'source_id' => $attributes['id']])->one();

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


    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['tasks/index']);
        }

        $tasks = Tasks::find()->where(['status' => Tasks::STATUS_NEW])->orderBy('dt_add DESC')->limit(4)->all();

        $this->layout = false;
        $loginForm = new LoginForm();
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
        return $this->render('landing', ['loginForm' => $loginForm, 'tasks' => $tasks]);
    }


    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
