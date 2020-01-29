<?php

use yii\rest\UrlRule;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'notificationHandler'],
    'controllerNamespace' => 'frontend\controllers',
    'name' => 'Best Task Tracker',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => $params['cookieValidationKey'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'domain' => $params['cookieDomain'], 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced',
            'cookieParams' => [
                'httpOnly' => true,
                'domain' => $params['cookieDomain'],
            ]
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
            'rules' => [
                [
                    'controller' => 'api/task',
                    'class' => UrlRule::class,
                    'extraPatterns' => [
                        'GET auth' => 'auth',
                    ]
                ]
            ],

        ],
    ],
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\Module',
        ],
    ],
    'params' => $params,
];
