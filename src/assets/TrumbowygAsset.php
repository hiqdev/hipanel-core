<?php declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class TrumbowygAsset extends AssetBundle
{
    public $sourcePath = null;
    public $css = ['https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css'];
    public $js = ['https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js'];
    public $depends = [
        JqueryAsset::class,
    ];
}
