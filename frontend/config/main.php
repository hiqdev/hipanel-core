<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

function d ($a) { die(var_dump($a)); }


return [
    'id' => 'app-frontend',
    'name'=>'hi Panel',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [

//        'assetManager' => [
//            'bundles' => false,
//        ],

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
                'api_url'=>'http://localhost-api.ahnames.com',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
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

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'hi3a' => [
                    'class'         => 'common\components\hi3aOauth2Client',
                    'authUrl'       => 'http://sol-auth-master.hiqdev.com/oauth2/authorize',
                    'tokenUrl'      => 'http://sol-auth-master.hiqdev.com/oauth2/token',
                    'apiBaseUrl'    => 'http://sol-auth-master.hiqdev.com/api',
                    'clientId'      => 'sol-hipanel-master',
                    'clientSecret'  => 'MdQybNMHMJ',
                ],
            ],
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
        'request' => [
            'cookieValidationKey' => 'MoMXqGrgnB3ffaQTZoaaIHRw3T_IPVaqlB',
            'enableCsrfValidation' => true
        ],
    ],
    'modules' => [
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ],
        'client' => [
            'class' => 'app\modules\client\Module',
        ],
        'thread' => [
            'class' => 'app\modules\thread\Module',
        ],
        'server' => [
            'class' => 'app\modules\server\Module',
        ],
    ],
    'params' => $params,
];
