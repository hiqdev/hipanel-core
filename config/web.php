<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

return [
    'id' => 'hipanel',
    'name' => 'HiPanel',
    'basePath' => dirname(__DIR__),
    'viewPath' => '@vendor/hiqdev/hisite/src/views',
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@root/runtime',
    'controllerNamespace' => 'hipanel\controllers',
    'bootstrap' => array_filter([
        'log' => 'log',
        'themeManager' => 'themeManager',
        'language' => 'language',
    ]),
    'components' => [
        'request' => [
            'enableCsrfCookie' => false,
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'response' => [
            'class' => \hipanel\components\Response::class,
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::class,
            'viewPath' => dirname(__DIR__) . '/src/mail',
        ],
        'orientationStorage' => [
            'class' => \hipanel\components\OrientationStorage::class,
        ],
        'uiOptionsStorage' => [
            'class' => \hipanel\components\UiOptionsStorage::class,
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
                'monitoring' => [
                    'except' => [
                        'yii\\web\HttpException:403',
                    ],
                ],
                'default' => [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
                'merchant' => [
                    'class' => \yii\log\FileTarget::class,
                    'logFile' => '@runtime/logs/merchant.log',
                    'categories' => ['merchant'],
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => \hiam\authclient\Collection::class,
            'clients' => [
                'hiam' => array_filter([
                    'class' => \hiam\authclient\HiamClient::class,
                    'scope' => $params['hiam.scope'],
                    'site' => $params['hiam.site'],
                    'authUrl' => $params['hiam.authUrl'],
                    'tokenUrl' => $params['hiam.tokenUrl'],
                    'apiBaseUrl' => $params['hiam.apiBaseUrl'],
                    'clientId' => $params['hiam.client_id'],
                    'clientSecret' => $params['hiam.client_secret'],
                ]),
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
                '$themedViewPaths' => [dirname(__DIR__) . '/src/views'],
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
                    'class' => \hipanel\assets\MomentAsset::class,
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hiqdev\thememanager\menus\AbstractNavbarMenu::class => [
                'class' => \hipanel\menus\NavbarMenu::class,
            ],
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'class' => \hipanel\menus\SidebarMenu::class,
            ],
            \hipanel\widgets\AdBanner::class => [
                'items' => $params['ad-banner.dashboard.items'],
            ],
            \hipanel\widgets\SidebarAdBanner::class => [
                'items' => $params['ad-banner.sidebar.items'],
            ],
        ],
        'singletons' => [
            \hipanel\widgets\filePreview\FilePreviewFactoryInterface::class => \hipanel\widgets\filePreview\FilePreviewFactory::class,
            \yii\web\Session::class => function () {
                return new \yii\web\Session();
            },
            \yii\web\User::class => function () {
                return Yii::$app->getUser();
            },
            \yii\caching\CacheInterface::class => function () {
                return Yii::$app->getCache();
            },
            \yii\authclient\Collection::class => function () {
                return Yii::$app->get('authClientCollection');
            },
            \hipanel\grid\RepresentationCollectionFinder::class => function ($container, $params, $config) {
                return \hipanel\grid\RepresentationCollectionFinder::forCurrentRoute('\hipanel\modules\%s\grid\%sRepresentations');
            },
        ],
    ],
];
