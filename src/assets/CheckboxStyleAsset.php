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

class CheckboxStyleAsset extends \yii\web\AssetBundle
{
    public $sourcePath = __DIR__ . '/css';
    public $css = ['checkbox-style-asset.css'];
    public $publishOptions = ['only' => ['checkbox-style-asset.css']];
}
