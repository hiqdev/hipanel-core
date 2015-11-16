<?php

use yii\helpers\ArrayHelper;

function d($a)
{
    print "<pre>";
    var_dump($a);
    debug_print_backtrace(0, 3);
    die();
}

$params = array_merge(
    require(Yii::getAlias('@hipanel/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params-local.php'))
);

/// Overrides default values for 3rd part modules
Yii::$container->set('kartik\widgets\DatePicker', function ($container, $params, $config) {
    return new \kartik\widgets\DatePicker(ArrayHelper::merge([
        'separator' => Yii::t('app', '&larr; between &rarr;')
    ], $config));
});

Yii::$container->set('kartik\date\DatePicker', function ($container, $params, $config) {
    return new \kartik\date\DatePicker(ArrayHelper::merge([
        'separator' => Yii::t('app', '&larr; between &rarr;')
    ], $config));
});

Yii::$container->set('kartik\field\FieldRange', function ($container, $params, $config) {
    return new \kartik\field\FieldRange(ArrayHelper::merge([
        'separator' => Yii::t('app', '&larr; between &rarr;')
    ], $config));
});

$config = [
    'vendorPath' => '@project/vendor',
    'components' => [
        'cache' => [
            'class' => 'hipanel\base\Cache',
            // 'cache' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'hipanel\base\AuthManager',
        ],
        'hiresource' => [
            'class'  => 'hipanel\base\Connection',
            'auth'   => function ($self) {
                if (Yii::$app->user->identity) {
                    return ['access_token' => Yii::$app->user->identity->getAccessToken()];
                }
                Yii::$app->user->loginRequired();

                return [];
            },
            'config' => [
                'api_url' => $params['api_url'],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];

if (YII_DEBUG && !YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => isset($params['debug_ips']) ? $params['debug_ips'] : '',
        'panels' => [
            'hiresource' => [
                'class' => 'hiqdev\hiart\DebugPanel',
            ]
        ]
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
