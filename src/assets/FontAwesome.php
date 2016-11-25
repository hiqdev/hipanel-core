<?php

namespace hipanel\assets;


use yii\web\AssetBundle;

class FontAwesome extends AssetBundle
{
    public $sourcePath = '@bower/components-font-awesome';

    public $css = [
         'css/font-awesome.min.css'
    ];
}
