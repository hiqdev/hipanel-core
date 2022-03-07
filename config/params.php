<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

$hipanelSite = $_ENV['HIPANEL_SITE'] ?? null;

return [
    'cache.driver'          => 'file',

    'adminEmail'            => '',
    'organization.url'      => '',
    'organization.termsUrl' => '',

    'hiam.site'             => '',
    'hiam.authUrl'          => null,
    'hiam.tokenUrl'         => null,
    'hiam.apiBaseUrl'       => null,
    'hiam.client_id'        => '',
    'hiam.client_secret'    => '',
    'hiam.scope'            => null,

    'hipanel.url'           => $hipanelSite ? "https://$hipanelSite/" : null,
    'hipanel.notPanel'      => false,

    'debug.allowedIps'      => [],
    'debug.event.enable'    => false,

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

    'ad-banner.dashboard.items' => [],
    'ad-banner.sidebar.items' => [],
];
