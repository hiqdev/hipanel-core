<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class RequestStateAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@frontend/assets';

    /**
     * @var array
     */
    public $js = [
        'js/RequestState.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
