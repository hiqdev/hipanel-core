<?php
declare(strict_types=1);

namespace hipanel\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;

class CondensedFromInputsAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/css';

    public $css = [
        'condensed-form-inputs.css',
    ];

    public $depends = [
        BootstrapAsset::class,
    ];
}
