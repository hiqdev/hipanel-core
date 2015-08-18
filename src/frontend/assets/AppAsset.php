<?php

namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/AppAssetFiles';
    public $css = [
        '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
        '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'css/panel.css',
    ];
    public $js = [
        'js/panel.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'raoul2000\widget\pnotify\PNotifyAsset',
        'hiqdev\assets\select2_bootstrap3_css\Select2Bootstrap3CssAsset',
    ];
}
