<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

use yii\web\AssetBundle;

class ReminderTopAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/assets';

    public $css = [
        'css/reminderTop.css',
    ];

    public $js = [
        'js/reminderTop.js',
    ];

    public $depends = [
        '\omnilight\assets\MomentAsset'
    ];
}
