<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 05.03.15
 * Time: 17:59
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class iCheckAsset extends AssetBundle
{
    public $sourcePath = '@bower/admin-lte';

    public $css = [
        'plugins/iCheck/all.css',
    ];

    public $js = [
        'plugins/iCheck/icheck.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'frontend\assets\AppAsset',
    ];
}
