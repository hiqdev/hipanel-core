<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

$config = [
    'id' => 'hipanel',
    'name' => 'HiPanel',
    'basePath' => dirname(__DIR__),
    'viewPath' => '@hisite/views',
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@root/runtime',
    'controllerNamespace' => 'hipanel\controllers',
    'bootstrap' => ['log', 'themeManager', 'language', 'menuManager'],
    'params' => $params,
    'aliases' => [
        '@bower'        => '@vendor/bower-asset',
        '@npm'          => '@vendor/npm-asset',
        '@vendor/bower' => '@vendor/bower-asset',
        '@vendor/npm'   => '@vendor/npm-asset',
        '@file'         => '/file',
        '@reminder'     => '/reminder'
    ],
    'components' => [
        'request' => [
            'enableCsrfCookie'    => true, /// XXX TO BE DISABLED
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'response' => [
            'class' => \hipanel\components\Response::class,
        ],
        'cache' => [
            'class' => \hipanel\components\Cache::class,
        ],
        'authManager' => [
            'class' => \hipanel\components\AuthManager::class,
        ],
        'hiart' => [
            'class' => \hipanel\components\Connection::class,
            'config' => [
                'base_uri' => $params['api_base_uri'],
            ],
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::class,
            'viewPath' => '@hipanel/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'i18n' => [
            'class' => \hipanel\components\I18N::class,
            'translations' => [
                'synt' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                    'fileMap' => [
                        'synt' => 'synt.php',
                    ],
                ],
                'hipanel' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                    'fileMap' => [
                        'hipanel' => 'hipanel.php',
                        'block-reasons' => 'block-reasons.php',
                    ],
                ],
                'app' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'cart' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@hiqdev/yii2/cart/messages',
                    'fileMap' => [
                        'merchant' => 'cart.php',
                    ],
                ],
                'hipanel/reminder' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@hipanel/messages',
                    'fileMap' => [
                        'hipanel/reminder' => 'reminder.php',
                    ],
                ],
            ],
        ],
        'orientationStorage' => [
            'class' => \hipanel\components\OrientationStorage::class,
        ],
        'user' => [
            'class' => \hipanel\components\User::class,
            'identityClass' => \hipanel\models\User::class,
            'enableAutoLogin' => true,
            'seller' => $params['user.seller'],
        ],
        'log' => [
            'traceLevel' => defined('YII_DEBUG') && YII_DEBUG ? 3 : 0,
            'targets' => [
                'default' => [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
                'merchant' => [
                    'class' => \yii\log\FileTarget::class,
                    'logFile' => '@runtime/logs/merchant.log',
                    'categories' => ['merchant'],
                ],
                'email' => [
                    'class' => \hipanel\log\EmailTarget::class,
                    'levels' => ['error'],
                    'message' => [
                        'from' => 'hipanel@hiqdev.com',
                        'to' => 'logs@hiqdev.com',
                        'subject' => 'HiPanel error log',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
            'class' => \hiam\authclient\Collection::class,
            'clients' => [
                'hiam' => [
                    'class' => \hiam\authclient\HiamClient::class,
                    'site' => $params['hiam_site'],
                    'clientId' => $params['hiam_client_id'],
                    'clientSecret' => $params['hiam_client_secret'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>' => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
                'file/<id:\d+>/<name:\S{1,128}>' => 'file/view',
            ],
        ],
        'formatter' => [
            'locale' => 'ru-RU',
            'nullDisplay' => '&nbsp;',
            'sizeFormatBase' => 1000,
        ],
        'themeManager' => [
            'defaultTheme' => 'adminlte',
            'assets' => [
                \hipanel\assets\AppAsset::class,
            ],
            'pathDirs' => [
                'hisite' => '@hipanel',
            ],
        ],
        'menuManager' => [
            'class' => \hiqdev\menumanager\MenuManager::class,
            'items' => [
                'sidebar' => [
                    'items' => [],
                ],
                'breadcrumbs' => [
                    'saveToView' => 'breadcrumbs',
                ],
            ],
        ],
        'fileStorage' => [
            'class' => \hipanel\components\FileStorage::class,
            'secret' => $params['fileStorageSecret'],
        ]
    ],
    'modules' => [
        'language' => [
            'class' => \hiqdev\yii2\language\Module::class,
            'languages' => [
                'en' => 'English',
                'ru' => 'Русский',
            ]
        ],
    ],
];

if (defined('YII_DEBUG') && YII_DEBUG) {
    // configuration adjustments when debug enabled
    $config['bootstrap']['debug'] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        'allowedIPs' => $params['debug_allowed_ips'],
        'panels' => [
            'hiart' => [
                'class' => \hiqdev\hiart\DebugPanel::class,
            ],
        ],
    ];
}

return $config;
