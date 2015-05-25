<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

function d ($a) { die(var_dump($a)); }

return [
    'id'                  => 'hipanel',
    'name'                => 'HiPanel',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log', 'pluginManager'],
    'defaultRoute'        => 'site',
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        're'                   => [
            'class' => 'app\components\Re',
        ],
        //        'assetManager' => [
        //            'bundles' => false,
        //        ],
        //        'activeresource'=>[
        //            'class'=>'frontend\components\ActiveResource\EActiveResource\EActiveResourceConnection',
        //            'site'=>'http://api.ahnames.com',
        //            'contentType'=>'application/json',
        //            'acceptType'=>'application/json',
        //            'queryCacheId'=>'SomeCacheComponent'
        //        ],
        'hiresource'           => [
            'class'  => 'hiqdev\hiart\Connection',
            'config' => [
                'api_url' => 'https://sol-ahcore-oauth.ahnames.com',
            ],
        ],
        'user'                 => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log'                  => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler'         => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
            'class'   => 'hi3a\authclient\Collection',
            'clients' => [
                'hi3a' => [
                    'class'        => 'hi3a\authclient\Hi3aClient',
                    'site'         => 'sol-hi3a-new.ahnames.com',
                    'clientId'     => $params['hi3a_client_id'],
                    'clientSecret' => $params['hi3a_client_secret'],
                ],
            ],
        ],
        'urlManager'           => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                '<_c:[\w\-]+>/<id:\d+>'              => '<_c>/view',
                '<_c:[\w\-]+>'                       => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
            ],
        ],
        'view'                 => [
            'class' => 'hipanel\base\View'
        ],
        'i18n'                 => [
            'translations' => [
                'app*' => [
                    'class'   => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'request'              => [
            'cookieValidationKey'  => 'MoMXqGrgnB3ffaQTZoaaIHRw3T_IPVaqlB',
            'enableCsrfValidation' => true
        ],
        'pluginManager' => [
            'class' => 'hiqdev\pluginmanager\Manager',
        ],
        'themeManager' => [
            'class' => 'hiqdev\thememanager\Manager',
            'defaultTheme'  => 'adminlte',
            'defaultSkin'   => 'skin-blue',
        ],
        'menuManager' => [
            'class' => 'hiqdev\menumanager\Manager',
            'items' => [
                'sidebar' => [
                    'items' => [
                        'header' => [
                            'label'     => 'MAIN NAVIGATION',
                            'options'   => ['class' => 'header'],
                        ],
                    ],
                ],
                'breadcrumbs' => [
                    'saveToView' => 'breadcrumbs',
                ],
            ],
        ],
    ],
    'modules'             => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ],
        'setting'  => [
            'class' => 'app\modules\setting\Module',
        ],
    ],
    'params'              => $params,
];

