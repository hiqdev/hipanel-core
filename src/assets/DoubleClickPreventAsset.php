<?php

namespace hipanel\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DoubleClickPreventAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/js';

    public $js = [
        'double-click-prevent.js',
    ];

    public $depends = [
        BootstrapAsset::class,
        JqueryAsset::class,
    ];
}
