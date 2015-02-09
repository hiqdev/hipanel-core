<?php

namespace frontend\modules\server\assets;
use yii\web\AssetBundle;

class OsSelectionAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@frontend/modules/server/assets';

    /**
     * @var array
     */
    public $js = [
        'js/OsSelection.js',
    ];

    public $css = [
        'css/OsSelection.css'
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
