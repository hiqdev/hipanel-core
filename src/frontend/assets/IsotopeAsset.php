<?php

namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class IsotopeAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/js';

    public $css = [
        'isotope.pkgd.min.js',
    ];
}