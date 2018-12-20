<?php


if (!defined('WEBAPP_ROOT_DIR')) {
    define('WEBAPP_ROOT_DIR', dirname(__DIR__));
}

if (!defined('WEBAPP_VENDOR_DIR')) {
    define('WEBAPP_VENDOR_DIR', WEBAPP_ROOT_DIR . '/vendor');
}

if (!file_exists(WEBAPP_VENDOR_DIR . '/autoload.php')) {
    die("Run composer to set up dependencies!\n");
}

require_once WEBAPP_VENDOR_DIR . '/autoload.php';
require_once hiqdev\composer\config\Builder::path('defines');
require_once WEBAPP_VENDOR_DIR . '/yiisoft/yii2/Yii.php';

Yii::setAlias('@root', WEBAPP_ROOT_DIR);
Yii::setAlias('@vendor', WEBAPP_VENDOR_DIR);
