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

class PincodePromptAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/assets/js';

    /**
     * @var array
     */
    public $js = [
        'pincode-prompt.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
