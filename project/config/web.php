<?php

use yii\gii\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\log\FileTarget;
use app\models\User;
use yii\caching\FileCache;
use yii\web\JsonParser;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'LibraryTest',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
            'enableCookieValidation' => false,
            'cookieValidationKey' => 's3jpQ9lyRnh8t-oFHseo-fM2ej7PtY7B',
        ],
        'response' => [
            'class' => Response::class,
            'format' => Response::FORMAT_JSON,
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG,
                ],
            ],
            'on beforeSend' => static function ($event) {
                $response = Yii::$app->response;
                $request = Yii::$app->request;
                if (!$response->isForbidden && !ArrayHelper::keyExists($response->statusCode, [401]) &&
                    ($request->isPost || $request->isAjax || $request->isPut || $request->isDelete)
                ) {
                    Yii::info([
                        'url' => $request->url,
                        'auth_user' => [
                            'userID' => Yii::$app->user->id ?: null,
                        ],
                        'requests' => [
                            'body' => $request->isPost ? [] : $request->getBodyParams(),
                            'get' => $_GET,
                            'post' => $_POST,
                            'file' => $_FILES,
                            'session' => Json::encode($_SESSION),
                            'server' => Json::encode($_SERVER),
                        ],
                        'response' => [
                            'statusCode' => $response->statusCode,
                            'data' => $response->data
                        ]
                    ], 'api');
                }
            }
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/url-rules.php'),
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
