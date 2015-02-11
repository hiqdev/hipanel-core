<?php

namespace frontend\modules\object\assets;
use yii\web\AssetBundle;

class RequestStateAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@frontend/modules/object/assets';

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
