<?php declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

class Vue3CdnAsset extends AssetBundle
{
    public $sourcePath = null;
    public $js = [
        (YII_DEBUG ? 'https://unpkg.com/vue@3' : 'https://unpkg.com/vue@3/dist/vue.global.prod.js'),
    ];
}
