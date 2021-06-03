<?php
declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

final class RefSearchAsset extends AssetBundle
{
    /**
     * {@inheridoc}
     */
    public $sourcePath = __DIR__;

    /**
     * {@inheridoc}
     */
    public $js = [
        'js/RefSearchAsset.js'
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
        'forceCopy' => true, //TODO: remove
    ];
}
