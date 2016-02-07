<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
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
