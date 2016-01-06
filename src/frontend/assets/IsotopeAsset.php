<?php

namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class IsotopeAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/js';

    public $js = [
//        'isotope.pkgd.js',
        'http://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.0.0/isotope.pkgd.js',
    ];
}