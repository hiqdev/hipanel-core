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

class StickySidebarAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/assets/js';

    /**
     * @var array
     */
    public $js = [
        'sticky-sidebar.min.js',
    ];
}
