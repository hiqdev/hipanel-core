<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

class RequestStateAsset extends \yii\web\AssetBundle
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
