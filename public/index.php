<?php

use hiqdev\composer\config\Builder;
use yii\web\Application;

(function () {
    require __DIR__ . '/../config/bootstrap.php';

    $config = require Builder::path(
        (defined('HISITE_TEST') && HISITE_TEST) ? 'web-test' : 'web'
    );

    (new Application($config))->run();
})();
