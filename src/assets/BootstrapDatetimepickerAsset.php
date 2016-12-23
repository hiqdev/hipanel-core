<?php

namespace hipanel\assets;

use yii\web\AssetBundle;

class BootstrapDatetimepickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/eonasdan-bootstrap-datetimepicker/build';

    public $js = [
        'js/bootstrap-datetimepicker.min.js',
    ];

    public $css = [
        'css/bootstrap-datetimepicker.min.css',
    ];

    public $depends = [
        \yii\web\JqueryAsset::class,
        \yii\bootstrap\BootstrapAsset::class,
        \hipanel\assets\MomentAsset::class
    ];
}
