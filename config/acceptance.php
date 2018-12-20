<?php

return [
    'PARAMS_LOCATION' => dirname(__DIR__, 2) . '/composer-config-plugin-output/acceptance.php',
    'YII2_CONFIG_LOCATION' => dirname(__DIR__) . '/tests/acceptance/config/suite.php',

    'COMMON_SUITE_LOCATION' => dirname(__DIR__) . '/tests/acceptance.suite.yml',
    'COMMON_TESTS_LOCATION' => dirname(__DIR__) . '/tests',

    'URL' => $params['hipanel.url'],
    'BROWSER' => 'chrome',
    'SELENIUM_HOST' => $params['tests.acceptance.selenium.host'],

    'client' => [
        'id' => '360113632',
        'login' => 'hipanel_test_user@hiqdev.com',
        'password' => 'random',
    ],

    'seller' => [
        'id' => '360113767',
        'login' => 'hipanel_test_reseller@hiqdev.com',
        'password' => 'random',
    ],

    'manager' => [
        'id' => '360087881',
        'login' => 'hipanel_test_manager@hiqdev.com',
        'password' => 'random',
    ],

    'admin' => [
        'id' => '360113706',
        'login' => 'hipanel_test_admin@hiqdev.com',
        'password' => 'random',
    ],
];
