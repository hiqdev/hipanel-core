<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class Combo2Asset extends AssetBundle {
    public $sourcePath = '@frontend/assets';

    public $js = [
        'combo2/combo2.js'
    ];

    public $depends = [
        'frontend\assets\Select2Asset'
    ];
}