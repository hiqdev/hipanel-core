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

class FontAwesome extends AssetBundle
{
    public $sourcePath = '@bower/components-font-awesome';
    public $css = [(YII_DEBUG ? 'css/font-awesome.css' : 'css/font-awesome.min.css')];
    public $publishOptions = [
        'only' => ['fonts/*', 'css/*'],
    ];
}
