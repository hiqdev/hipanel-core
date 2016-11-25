<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

use hiqdev\assets\pnotify\PNotifyAsset;
use hiqdev\assets\select2_bootstrap3_css\Select2Bootstrap3CssAsset;
use hiqdev\combo\ComboAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/assets/AppAssetFiles';
    public $css = [
        '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'css/panel.css',
    ];
    public $js = [
        'js/panel.js',
        'js/ResizeSensor.js',
        'js/ElementQueries.js',
    ];
    public $depends = [
        \yii\web\YiiAsset::class,
        \yii\bootstrap\BootstrapAsset::class,
        \yii\bootstrap\BootstrapPluginAsset::class,
        FontAwesome::class,
        LessSpaceAsset::class,
        // Adds Resizable columns JS and CSS to all pages
        // This is a temporary fix because of https://github.com/yiisoft/yii2/issues/2310
        // On Pjax page loading, ajax prefilter removes all CSS styles that are not on the main page
        \hiqdev\yii2\assets\JqueryResizableColumns\ResizableColumnsAsset::class,
        ComboAsset::class,
        PNotifyAsset::class,
        Select2Bootstrap3CssAsset::class,
    ];
}
