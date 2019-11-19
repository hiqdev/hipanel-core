<?php


namespace hipanel\assets;


use yii\web\AssetBundle;

class AssignAttributesAsset extends AssetBundle
{
    /**
     * {@inheridoc}
     */
    public $sourcePath = '@hipanel/assets';

    /**
     * {@inheridoc}
     */
    public $js = [
        'js/AssignAttributes.js'
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
        'forceCopy' => true,
        'linkAssets' => true,
        'appendTimestamp' => true,
    ];
}
