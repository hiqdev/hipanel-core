<?php

return [
    'components' => [
        'hiresource' => [
            'class'  => 'hiqdev\hiart\Connection',
            'config' => [
                'api_url' => 'http://hiapi.hipanel.com',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
