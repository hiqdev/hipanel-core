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
    'orgUrl'                => '',

    'api_base_uri'          => '',
    'hiam_site'             => '',
    'hiam_client_id'        => '',
    'hiam_client_secret'    => '',
    'user.seller'           => '',
    'cookieValidationKey'   => '',
    'debug_allowed_ips'     => [],
    'adminEmail'            => 'admin@example.com',
    'fileStorageSecret'     => null,

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
