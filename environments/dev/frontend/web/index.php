<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../vendor/hiqdev/hipanel-core/src/common/config/bootstrap.php';
require __DIR__ . '/../../vendor/hiqdev/hipanel-core/src/frontend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require(Yii::getAlias('@hipanel/common/config/main.php')),
    require(Yii::getAlias('@project/common/config/main.php')),
    require(Yii::getAlias('@project/common/config/main-local.php')),
    require(Yii::getAlias('@hipanel/frontend/config/main.php')),
    require(Yii::getAlias('@project/frontend/config/main.php')),
    require(Yii::getAlias('@project/frontend/config/main-local.php'))
);

$application = new yii\web\Application($config);
$application->run();
