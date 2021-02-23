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
    'PARAMS_LOCATION' => dirname(__DIR__, 4) . '/vendor/yiisoft/composer-config-plugin-output/acceptance.php',
    'YII2_CONFIG_LOCATION' => dirname(__DIR__) . '/tests/acceptance/config/suite.php',

    'COMMON_SUITE_LOCATION' => dirname(__DIR__) . '/tests/acceptance.suite.yml',
    'COMMON_TESTS_LOCATION' => dirname(__DIR__) . '/tests',

    'URL' => getenv('URL'),
    'BROWSER' => 'chrome',
    'SELENIUM_HOST' => getenv('TESTS_ACCEPTANCE_SELENIUM_HOST'),

    'client' => [
        'id' => null,
        'login' => 'hipanel_test_user',
        'password' => 'random',
    ],

    'seller' => [
        'id' => null,
        'login' => 'hipanel_test_reseller',
        'password' => 'random',
    ],

    'manager' => [
        'id' => null,
        'login' => 'hipanel_test_manager',
        'password' => 'random',
    ],

    'admin' => [
        'id' => null,
        'login' => 'hipanel_test_admin',
        'password' => 'random',
    ],
];
