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
use yii\web\JqueryAsset;

class IsotopeAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/js';

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
