<?php

namespace hipanel\frontend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/frontend/assets/AppAssetFiles';
    public $css = [
        '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
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
        'hiqdev\assets\select2_bootstrap3_css\Select2Bootstrap3CssAsset',
        // Adds Resizable columns JS and CSS to all pages
        // This is a temporary fix because of https://github.com/yiisoft/yii2/issues/2310
        // On Pjax page loading, ajax prefilter removes all CSS styles that are not on the main page
        'hiqdev\yii2\assets\JqueryResizableColumns\ResizableColumnsAsset',
        'hiqdev\assets\pnotify\PNotifyAsset',
    ];
}
