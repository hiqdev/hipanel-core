<?php
return [
    'components' => [
        'hiresource' => [
            'class'  => 'hiqdev\hiart\Connection',
            'config' => [
                'api_url' => 'https://sol-ahcore-oauth.ahnames.com',
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
