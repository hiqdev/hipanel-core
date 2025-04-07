<?php

declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/file-input';
    public $css = ['file-input.css'];
    public $js = ['file-input.js'];
}
