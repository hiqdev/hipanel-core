<?php
namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class HipanelAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/js';

    public $js = [
        'hipanel.js',
    ];
}