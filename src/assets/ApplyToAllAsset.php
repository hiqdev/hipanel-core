<?php


namespace hipanel\assets;


use yii\web\AssetBundle;

class ApplyToAllAsset extends AssetBundle
{
    /**
     * {@inheridoc}
     */
    public $sourcePath = '@hipanel/assets';

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
