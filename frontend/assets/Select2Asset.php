<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Widget select2 asset bundle.
 */
class Select2Asset extends AssetBundle
{
    /**
     * @var
     */
    public $language;

    /**
     * @var string
     */
    public $sourcePath = '@bower/select2';

    /**
     * @var array
     */
    public $css = [
        'dist/css/select2.min.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'dist/js/select2.full.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function registerAssetFiles($view)
    {
        if ($this->language !== null) {
            $this->js[] = 'dist/js/i18n/' . $this->language . '.js';
        }
        parent::registerAssetFiles($view);
    }
}
