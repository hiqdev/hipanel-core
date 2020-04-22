<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

if (!defined('WEBAPP_ROOT_DIR')) {
    define('WEBAPP_ROOT_DIR', dirname(__DIR__, 4));
}
if (!defined('WEBAPP_VENDOR_DIR')) {
    define('WEBAPP_VENDOR_DIR', WEBAPP_ROOT_DIR . '/vendor');
}

if (!file_exists(WEBAPP_VENDOR_DIR . '/autoload.php')) {
    die("Run composer to set up dependencies!\n");
}

require_once WEBAPP_VENDOR_DIR . '/autoload.php';
require_once Yiisoft\Composer\Config\Builder::path('constants');
require_once WEBAPP_VENDOR_DIR . '/yiisoft/yii2/Yii.php';

Yii::setAlias('@root', WEBAPP_ROOT_DIR);
Yii::setAlias('@vendor', WEBAPP_VENDOR_DIR);
