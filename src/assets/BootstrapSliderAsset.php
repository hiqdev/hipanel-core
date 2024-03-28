<?php

declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class BootstrapSliderAsset extends AssetBundle
{
    public $sourcePath = '@bower/seiyria-bootstrap-slider/dist';
    public $js = ['bootstrap-slider' . (YII_DEBUG ? '.js' : '.min.js')];
    public $css = ['css/bootstrap-slider' . (YII_DEBUG ? '.css' : '.min.css')];

    public $depends = [
        JqueryAsset::class,
    ];
}
