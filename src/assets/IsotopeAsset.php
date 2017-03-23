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

class IsotopeAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/assets/js';

    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
