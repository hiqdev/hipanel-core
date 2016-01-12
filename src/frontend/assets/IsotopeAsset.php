<?php

namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class IsotopeAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/js';

    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js',
    ];
}
