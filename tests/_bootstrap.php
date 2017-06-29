<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\web\Application;
use hiqdev\composer\config\Builder;

Yii::setAlias('@root', dirname(__DIR__));
$config = require Builder::path('web');
Yii::$app = new Application($config);
