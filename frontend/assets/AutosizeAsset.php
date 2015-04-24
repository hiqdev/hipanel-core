<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class AutosizeAsset extends AssetBundle
{
    public $sourcePath = '@bower/autosize';

    public $css = [
    ];

    public $js = [
        'dest/autosize.min.js',
    ];

    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
//        'frontend\assets\AppAsset',
    ];
}
