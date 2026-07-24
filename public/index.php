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
use yii\web\Application;
use hidev\webapp\components\TestEndpoints;

(static function () {
    require_once(dirname(__DIR__, 2) . '/hidev-webapp/src/components/TestEndpoints.php');
    TestEndpoints::try();

    require __DIR__ . '/../config/bootstrap.php';

    $host = $_SERVER['HTTP_HOST'];
    $type = (defined('HISITE_TEST') && HISITE_TEST) ? 'web-test' : 'web';
    $path = Builder::path($host . '/' . $type);
    if (!file_exists($path)) {
        $path = Builder::path($type);
    }

    $config = require $path;

    (new Application($config))->run();
})();
