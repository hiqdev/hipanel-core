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
    public $sourcePath = '@frontend/assets';

    /**
     * @var array
     */
    public $css = [
        'select2-3.5.2/select2.css',
        'select2-3.5.2/select2-kv.css',
//        'select2-3.5.2/select2-bootstrap.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'select2-3.5.2/select2.js',
//        'select2-3.5.2/select2-kv.js',
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
            $this->js[] = 'select2-3.5.2/select2_locale_' . $this->language . '.js';
        }
        parent::registerAssetFiles($view);
    }
}
