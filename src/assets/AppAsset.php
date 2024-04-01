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

use hiqdev\assets\select2_bootstrap3_css\Select2Bootstrap3CssAsset;
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
        FontAwesome::class,
        LessSpaceAsset::class,
        Select2Bootstrap3CssAsset::class,
        HipanelAsset::class,
        CondensedFromInputsAsset::class,
    ];
}
