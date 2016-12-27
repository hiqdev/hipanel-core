<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

return [
    'id' => 'hipanel',
    'name' => 'HiPanel',
    'basePath' => dirname(__DIR__),
    'viewPath' => '@hisite/views',
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@root/runtime',
    'controllerNamespace' => 'hipanel\controllers',
    'bootstrap' => [
        'log' => 'log',
        'themeManager' => 'themeManager',
        'language' => 'language',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@vendor/bower' => '@vendor/bower-asset',
        '@vendor/npm' => '@vendor/npm-asset',
        '@file' => '/file',
        '@reminder' => '/reminder',
    ],
    'components' => [
        'request' => [
            'enableCsrfCookie' => false,
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'response' => [
            'class' => \hipanel\components\Response::class,
        ],
        'cache' => [
            'class' => \hipanel\components\Cache::class,
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
                'hipanel' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                ],
                'hipanel:synt' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                ],
                'hipanel:block-reasons' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
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
            'pathMap' => [
                '$themedViewPaths' => ['@hipanel/views'],
            ],
        ],
        'fileStorage' => [
            'class' => \hipanel\components\FileStorage::class,
            'secret' => $params['fileStorageSecret'],
        ],
        'settingsStorage' => [
            'class' => \hipanel\components\SettingsStorage::class,
        ],
        'themeSettingsStorage' => [
            'class' => \hipanel\components\ThemeSettingsStorage::class,
        ],
        'assetManager' => [
            'bundles' => [
                \omnilight\assets\MomentAsset::class => [
                    'class' => \hipanel\assets\MomentAsset::class
                ]
            ]
        ]
    ],
    'modules' => [
        'language' => [
            'languages' => [
                'en' => 'English',
                'ru' => 'Русский',
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hipanel\components\ApiConnectionInterface::class => function () {
                return Yii::$app->get('hiart');
            },
            \hiqdev\thememanager\menus\AbstractNavbarMenu::class => [
                'class' => \hipanel\menus\NavbarMenu::class,
            ],
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'class' => \hipanel\menus\SidebarMenu::class,
            ],
        ],
    ],
];
