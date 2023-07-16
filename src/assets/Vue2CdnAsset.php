<?php
declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

class Vue2CdnAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $js = ['https://cdn.jsdelivr.net/npm/vue@^2'];
}
