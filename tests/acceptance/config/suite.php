<?php

use hiqdev\composer\config\Builder;

$config = \yii\helpers\ArrayHelper::merge(
    require Builder::path('web-test'),
    [
        'bootstrap' => [
            'debug' => new \yii\helpers\UnsetArrayValue(),
        ],
        'modules' => [
            'debug' => new \yii\helpers\UnsetArrayValue(),
        ],
        'components' => [
            'errorHandler' => [
                'errorAction' => new \yii\helpers\UnsetArrayValue(),
            ],
        ],
    ]
);

return $config;
