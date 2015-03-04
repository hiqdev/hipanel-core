<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 04.03.15
 * Time: 18:29
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@app/themes/adminlte/assets';
    public $css = [
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
        'http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css',
        'css/morris/morris.css',
        'css/AdminLTE.css',
    ];
    public $js = [
        'js/AdminLTE/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}