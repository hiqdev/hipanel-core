<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name'=>'hi Panel',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [

        're'=>[
            'class'=>'app\components\Re',
        ],
//        'activeresource'=>[
//            'class'=>'frontend\components\ActiveResource\EActiveResource\EActiveResourceConnection',
//            'site'=>'http://api.ahnames.com',
//            'contentType'=>'application/json',
//            'acceptType'=>'application/json',
//            'queryCacheId'=>'SomeCacheComponent'
//        ],

        'hiresource'=>[
            'class' => 'frontend\components\hiresource\Connection',
            'config' => [
                'api_url'=>'tofid-api.ahnames.com',
            ],
        ],

        'user' => [
            'identityClass' => 'common\m  odels\User',
            'enableAutoLogin' => true,
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
                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>' => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/adminlte',
//                    '@app/modules' => '@frontend/themes/adminlte/modules',
                ],
                'baseUrl'   => '@web/themes/adminlte'
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'client' => [
            'class' => 'app\modules\client\Module',
        ],
        'ticket' => [
            'class' => 'app\modules\ticket\Module',
        ],
    ],
    'params' => $params,
];
