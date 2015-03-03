<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 27.02.15
 * Time: 16:51
 */

namespace frontend\assets\LightBoxAsset;

use kartik\base\AssetBundle;

class LightBoxAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/LightBoxAsset/asset';

    public $css = [
        'css/lightbox.css',
    ];

    public $js = [
        'js/lightbox.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}