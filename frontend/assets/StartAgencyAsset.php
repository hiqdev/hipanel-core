<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 02.04.15
 * Time: 18:15
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StartAgencyAsset extends AssetBundle
{
    public $sourcePath = '@bower/startbootstrap-agency';

    public $css = [
        'css/agency.css',

        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',

        // Extra fonts
        'http://fonts.googleapis.com/css?family=Montserrat:400,700',
        'http://fonts.googleapis.com/css?family=Kaushan+Script',
        'http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic',
        'http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700',
    ];

    public $js = [
        // Plugin JavaScript
        'http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js',
        'js/classie.js',
        'js/cbpAnimatedHeader.js',

        // Contact Form JavfaScript
        'js/jqBootstrapValidation.js',
        'js/contact_me.js',

        // Custom Theme JavaScript
        'js/agency.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'frontend\assets\SiteAsset'
    ];
}