<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 03.03.15
 * Time: 18:34
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class AdminLte2Asset extends AssetBundle
{
    public $sourcePath = '@bower/admin-lte';
    public $css = [
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
        'http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'dist/css/AdminLTE.css',
        'dist/css/skins/_all-skins.css',

    ];
    public $js = [
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'plugins/fastclick/fastclick.min.js',
        'dist/js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'frontend\assets\AppAsset',
    ];
}