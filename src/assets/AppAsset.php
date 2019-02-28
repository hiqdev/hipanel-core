<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

use hiqdev\assets\pnotify\PNotifyAsset;
use hiqdev\assets\select2_bootstrap3_css\Select2Bootstrap3CssAsset;
use hiqdev\combo\ComboAsset;
use hiqdev\yii2\assets\JqueryResizableColumns\ResizableColumnsAsset;
use Yii;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/assets/AppAssetFiles';
    public $css = [
        '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'css/panel.css',
    ];
    public $js = [
        'js/panel.js',
        '//polyfill.io/v2/polyfill.min.js?features=IntersectionObserver',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FontAwesome::class,
        LessSpaceAsset::class,
        ComboAsset::class,
        PNotifyAsset::class,
        Select2Bootstrap3CssAsset::class,
        HipanelAsset::class,
        MomentAsset::class,
        ElementQueryAsset::class,
        ResizableColumnsAsset::class,
        CheckboxesAsset::class,
    ];
}
