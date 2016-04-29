<?php
namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class ElementQueryAsset extends AssetBundle
{
    public $sourcePath = '@vendor/tysonmatanich/elementQuery';

    public $js = [
        'elementQuery.min.js',
    ];
}