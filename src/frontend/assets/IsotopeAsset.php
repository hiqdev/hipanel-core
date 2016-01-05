<?php

namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class LessSpaceAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/js';

    public $css = [
        'isotope.pkgd.min.js',
    ];
}