<?php

namespace hipanel\assets;

use yii\web\AssetBundle;

class CheckboxesAsset extends AssetBundle
{
    public $sourcePath = '@npm/checkboxes.js';

    public $js = [
        'dist/jquery.checkboxes-1.2.0' . (YII_DEBUG ? '.js' : '.min.js')
    ];
}
