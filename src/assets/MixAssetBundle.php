<?php declare(strict_types=1);

namespace hipanel\assets;

use yii\helpers\Json;
use yii\web\AssetBundle;

class MixAssetBundle extends AssetBundle
{
    public function init(): void
    {
        parent::init();
        $path = $this->sourcePath . '/mix-manifest.json';
        if (is_file($path)) {
            $files = Json::decode(file_get_contents($path));
            if (empty($files)) {
                return;
            }
            foreach ($files as $file) {
                $this->js[] = ltrim($file, '/');
            }
        }
    }
}
