<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]

        ],
        'user' => [
            'identityClass' => 'frontend\models\Users',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/index'],
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'user' => 'users/index',
                'task' => 'tasks/index',
                'user/view/<id:\d+>' => 'users/view',
                'task/view/<id:\d+>' => 'tasks/view',
                '' => 'site/index',
                'task/new' => 'tasks/create',
                'geo/index/<query:\d+>' => 'geo/index',
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/tasks',
                    ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/messages', 'pluralize' => false],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'GMT+3',
        ]
    ],
    'on beforeAction' => function() {
        if (Yii::$app->user->identity) {
            $user = \frontend\models\Users::findOne(Yii::$app->user->id);
            $user->dt_last_activity = date('Y-m-d H:i:s');
            $user->save();
        }
    },
    'params' => $params,
];
