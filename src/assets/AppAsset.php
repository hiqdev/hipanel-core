<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

use hiqdev\assets\pnotify\PNotifyAsset;
use hiqdev\assets\select2_bootstrap3_css\Select2Bootstrap3CssAsset;
use hiqdev\yii2\assets\select2\Select2Asset;
use hiqdev\yii2\reminder\ReminderTopAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class AppAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/AppAssetFiles';
    public $css = [
        'css/panel.css',
    ];
    public $js = [
        'js/panel.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FontAwesome::class,
        LessSpaceAsset::class,
        Select2Bootstrap3CssAsset::class,
        Select2Asset::class,
        HipanelAsset::class,
        HiPanelChartAsset::class,
        CondensedFromInputsAsset::class,
        PNotifyAsset::class,
        ReminderTopAsset::class,
        CheckboxStyleAsset::class,
    ];
}
