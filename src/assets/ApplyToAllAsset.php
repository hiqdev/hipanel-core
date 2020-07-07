<?php


namespace hipanel\assets;


use yii\web\AssetBundle;

class ApplyToAllAsset extends AssetBundle
{
    /**
     * {@inheridoc}
     */
    public $sourcePath = __DIR__;

    /**
     * {@inheridoc}
     */
    public $js = [
        'js/ApplyToAll.js'
    ];

    /**
     * {@inheridoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * {@inheridoc}
     */
    public $publishOptions = [
        'linkAssets' => true,
    ];
}
