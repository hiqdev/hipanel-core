<?php
declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

class Vue2CdnAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $js = [(YII_DEBUG ? 'https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js' : 'https://cdn.jsdelivr.net/npm/vue@2')];
}
