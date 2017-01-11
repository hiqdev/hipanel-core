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
    'adminEmail'            => '',
    'orgUrl'                => '',

    'api_base_uri'          => '',

    'hiam.site'             => '',
    'hiam.client_id'        => '',
    'hiam.client_secret'    => '',

    'user.seller'           => '',
    'cookieValidationKey'   => '',
    'debug_allowed_ips'     => [],
    'fileStorageSecret'     => null,
    'currency'              => 'usd',

    'poweredByName'         => 'HiPanel',
    'poweredByUrl'          => 'https://hipanel.com/',

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
