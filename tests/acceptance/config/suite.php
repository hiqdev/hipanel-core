<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

use Yiisoft\Composer\Config\Builder;

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
