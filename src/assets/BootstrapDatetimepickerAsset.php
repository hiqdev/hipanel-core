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

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class BootstrapDatetimepickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/eonasdan-bootstrap-datetimepicker/build';
    public $js = ['js/bootstrap-datetimepicker.min.js'];
    public $css = ['css/bootstrap-datetimepicker.min.css'];
    public $depends = [
        JqueryAsset::class,
        BootstrapAsset::class,
        MomentAsset::class,
    ];
}
