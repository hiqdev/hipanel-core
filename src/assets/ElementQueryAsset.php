<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

use yii\web\AssetBundle;

class ElementQueryAsset extends AssetBundle
{
    public $sourcePath = '@bower/css-element-queries/src';

    public $js = [
        'ResizeSensor.js',
        'ElementQueries.js',
    ];
}
