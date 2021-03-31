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

class HipanelAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/js';

    public $js = [
        'hipanel.js',
    ];

    public function registerAssetFiles($view)
    {
        $locale = \Yii::$app->language;
        $view->registerJs("hipanel.locale.set('$locale');");
        parent::registerAssetFiles($view);
    }

    public $depends = [
        DoubleClickPreventAsset::class,
    ];
}
