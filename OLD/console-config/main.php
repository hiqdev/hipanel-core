<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

$params = array_merge(
    require(Yii::getAlias('@hipanel/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params-local.php')),
    require(Yii::getAlias('@hipanel/console/config/params.php')),
    require(Yii::getAlias('@project/console/config/params.php')),
    require(Yii::getAlias('@project/console/config/params-local.php'))
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'runtimePath' => '@project/console/runtime',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
