<?php

return [
    'api_url'               => '',
    'hiam_site'             => '',
    'hiam_client_id'        => '',
    'hiam_client_secret'    => '',
    'user.seller'           => '',
    'cookieValidationKey'   => '',
    'debug_allowed_ips'     => [],
    'adminEmail'            => 'admin@example.com',

    'skin' => [
        'default-skin' => 'skin-blue',
        'default-theme' => 'adminlte',
    ],

    'pjax'       => [
        'id'              => 'content-pjax',
        'timeout'         => 0,
        'enablePushState' => true,
        'options'         => [
            'data' => [
                'pjax-contaiter' => true,
            ],
        ],
    ],
];
