<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

use yii\web\AssetBundle;

class CheckboxesAsset extends AssetBundle
{
    public $sourcePath = '@npm/checkboxes.js';

    public $js = [
        'dist/jquery.checkboxes-1.2.0' . (YII_DEBUG ? '.js' : '.min.js'),
    ];
}
