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
    'adminEmail' => 'admin@example.com',
    'pjax'       => [ /// Config for the main PJAX page wrapper
        'id'              => 'content-pjax',
        'timeout'         => 0,
        'enablePushState' => true,
        'options'         => [
            'data' => [
                'pjax-contaiter' => true,
            ],
        ],
    ],
    'skin' => [
        'default-skin' => 'skin-blue',
        'default-theme' => 'adminlte',
    ],
];
