<?php

$params = array_merge(
    require(Yii::getAlias('@hipanel/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params-local.php')),
    require(Yii::getAlias('@hipanel/frontend/config/params.php')),
    require(Yii::getAlias('@project/frontend/config/params.php')),
    require(Yii::getAlias('@project/frontend/config/params-local.php'))
);

return [
    'id'                  => 'hipanel',
    'name'                => 'HiPanel',
    'basePath'            => dirname(__DIR__),
    'runtimePath'         => '@project/frontend/runtime',
    'bootstrap'           => ['log', 'pluginManager'],
    'defaultRoute'        => 'site',
    'controllerNamespace' => 'frontend\controllers',
    'language'            => 'en',
    'sourceLanguage'      => 'en-US',
    'components'          => [
        'user' => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
            'class'   => 'hiam\authclient\Collection',
            'clients' => [
                'hiam' => [
                    'class'        => 'hiam\authclient\HiamClient',
                    'site'         => $params['hiam_site'],
                    'clientId'     => $params['hiam_client_id'],
                    'clientSecret' => $params['hiam_client_secret'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'hipanel\base\LanguageUrlManager',
            'languages' => [
                'en' => 'en-US',
                'ru' => 'ru-RU'
            ],
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'enableStrictParsing' => false,
            'rules'           => [
                '<_c:[\w\-]+>/<id:\d+>'              => '<_c>/view',
                '<_c:[\w\-]+>'                       => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
            ],
        ],
        'view' => [
            'class' => 'hipanel\base\View'
        ],
        'i18n' => [
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
        'formatter' => [
            'locale'      => 'ru-RU',
            'nullDisplay' => '&nbsp;',
        ],
        'request' => [
            'cookieValidationKey'  => 'MoMXqGrgnB3ffaQTZoaaIHRw3T_IPVaqlB',
            'enableCsrfValidation' => true
        ],
        'pluginManager' => [
            'class' => 'hiqdev\pluginmanager\PluginManager',
        ],
        'themeManager' => [
            'class'  => 'hiqdev\thememanager\ThemeManager',
            'defaultTheme' => 'adminlte',
            'assets' => [
                'frontend\assets\AppAsset',
            ],
        ],
        'menuManager' => [
            'class' => 'hiqdev\menumanager\MenuManager',
            'items' => [
                'sidebar' => [
                    'items' => [
//                        'header' => [
//                            'label'     => 'MAIN NAVIGATION',
//                            'options'   => ['class' => 'header'],
//                        ],
                    ],
                ],
                'breadcrumbs' => [
                    'saveToView' => 'breadcrumbs',
                ],
            ],
        ],
    ],
    'modules' => [
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
    'params' => $params,
];

