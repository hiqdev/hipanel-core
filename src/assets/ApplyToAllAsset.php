<?php

declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ApplyToAllAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/js';
    public $js = ['ApplyToAll.js'];
    public $depends = [
        JqueryAsset::class,
    ];
    public $publishOptions = ['only' => ['ApplyToAll.js']];
}
