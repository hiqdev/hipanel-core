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

(static function () {
    $constantsFile = include Yiisoft\Composer\Config\Builder::path('constants');
    foreach ($constantsFile as $path) {
        require_once $path;
    }
})();

error_reporting(YII_ENV === 'prod'
    ? E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING
    # XXX to be fixed to E_ALL & ~E_NOTICE & ~E_STRICT for non prod
    : E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED
);

require_once WEBAPP_VENDOR_DIR . '/yiisoft/yii2/Yii.php';

Yii::setAlias('@root', WEBAPP_ROOT_DIR);
Yii::setAlias('@vendor', WEBAPP_VENDOR_DIR);
