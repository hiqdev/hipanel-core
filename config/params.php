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
    'adminEmail'            => '',
    'organization.url'      => '',
    'organization.termsUrl' => '',

    'hiam.site'             => '',
    'hiam.authUrl'          => null,
    'hiam.tokenUrl'         => null,
    'hiam.apiBaseUrl'       => null,
    'hiam.client_id'        => '',
    'hiam.client_secret'    => '',

    'hipanel.url'           => null,
    'hipanel.notPanel'      => false,

    'debug.allowedIps'      => [],

    'user.seller'           => '',

    'currency'              => 'usd',

    'cookieValidationKey'   => '',
    'fileStorageSecret'     => null,

    'poweredBy.name'        => 'HiPanel',
    'poweredBy.url'         => 'https://hipanel.com/',

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

    'mailing.service.submitUrl' => '',

    'tests.acceptance.selenium.host' => null,
];
