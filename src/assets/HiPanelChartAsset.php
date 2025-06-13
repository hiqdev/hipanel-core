<?php declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

class HiPanelChartAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/js';

    public $js = [
        'HiPanelChart.js',
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
    }
}
