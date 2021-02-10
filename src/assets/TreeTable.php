<?php

namespace hipanel\assets;

use yii\web\AssetBundle;

class TreeTable extends AssetBundle
{
    public $sourcePath = '@npm/jquery-treetable';

    public $css = [
        'css/jquery.treetable.css',
    ];

    public $js = [
        'jquery.treetable.js',
    ];
}
