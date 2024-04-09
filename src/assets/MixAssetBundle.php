<?php

declare(strict_types=1);

namespace hipanel\assets;

use yii\web\AssetBundle;

class MixAssetBundle extends AssetBundle
{
    public function init(): void
    {
        parent::init();
        $path = $this->sourcePath . '/mix-manifest.json';
        if (is_file($path)) {
            $content = json_decode(file_get_contents($path));
            $file = ltrim(reset($content), '/');
            $this->js[] = $file;
        }
    }
}
