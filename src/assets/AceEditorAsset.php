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
use yii\web\JqueryAsset;

/**
 * Class AceEditorAsset
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class AceEditorAsset extends AssetBundle
{
    public $sourcePath = YII_DEBUG
        ? '@npm/ace-builds/src'
        : '@npm/ace-builds/src-min';

    public $js = [
        'ace.js',
        'mode-javascript.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
