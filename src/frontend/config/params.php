<?php
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
