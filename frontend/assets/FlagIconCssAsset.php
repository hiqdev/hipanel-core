<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 31.03.15
 * Time: 18:27
 */
namespace frontend\assets;
use yii\web\AssetBundle;

class FlagIconCssAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/flag-icon-css';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/flag-icon.min.css',
    ];

    public $js = [
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];
}