<?php

$params = require COMPOSER_CONFIG_PLUGIN_DIR . '/params.php';

$config = [
    'id' => 'hipanel',
    'name' => 'HiPanel',
    'basePath' => dirname(__DIR__),
    'viewPath' => '@hisite/views',
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@root/runtime',
    'controllerNamespace' => 'hipanel\controllers',
    'bootstrap' => ['log', 'themeManager', 'urlManager', 'menuManager'],
    'params' => $params,
    'aliases' => [
        'bower' => '@vendor/bower-asset',
        'npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'response' => [
            'class' => \hipanel\base\Response::class,
        ],
        'cache' => [
            'class' => \hipanel\base\Cache::class,
        ],
        'authManager' => [
            'class' => \hipanel\base\AuthManager::class,
        ],
        'hiart' => [
            'class' => \hipanel\base\Connection::class,
            'auth' => function () {
                if (Yii::$app->user->identity) {
                    return ['access_token' => Yii::$app->user->identity->getAccessToken()];
                }

                if (Yii::$app->user->loginRequired() !== null) {
                    Yii::trace('Login is required. Redirecting to the login page', 'hipanel');
                    Yii::$app->response->send();
                    Yii::$app->end();
                }

                return [];
            },
            'config' => [
                'api_url' => $params['api_url'],
                'base_uri' => $params['api_url'],
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
            'class' => \hipanel\base\I18N::class,
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
                ]
            ],
        ],
        'orientationStorage' => [
            'class' => \hipanel\base\OrientationStorage::class,
        ],
        'user' => [
            'class' => \hipanel\base\User::class,
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
            'class' => \hipanel\base\LanguageUrlManager::class,
            'languages' => [
                'en' => 'en-US',
                'ru' => 'ru-RU',
            ],
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
        'view' => [
            'class' => \hipanel\base\View::class,
        ],
        'formatter' => [
            'locale' => 'ru-RU',
            'nullDisplay' => '&nbsp;',
            'sizeFormatBase' => 1000,
        ],
        'themeManager' => [
            'class' => \hiqdev\thememanager\ThemeManager::class,
            'theme' => 'adminlte',
            'assets' => [
                \hipanel\assets\AppAsset::class,
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
    ],
    'modules' => [],
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
