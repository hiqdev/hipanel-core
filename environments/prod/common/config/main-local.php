<?php
return [
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'hipanel\base\AuthManager',
        ],
        'hiresource' => [
            'class'  => 'hiqdev\hiart\Connection',
            'config' => [
                'api_url' => 'http://hiapi.ahnames.com',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
    'aliases' => [
        '@hipanel' => dirname(dirname(__DIR__)),
    ],
];
