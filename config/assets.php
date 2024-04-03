<?php

declare(strict_types=1);

Yii::setAlias('@bower', '@vendor/bower-asset');
Yii::setAlias('@npm', '@vendor/npm-asset');
Yii::setAlias('@webroot', dirname(__DIR__, 4) . '/public');
Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'java -jar core/lib/closure-compiler-v20240317.jar --js {from} --js_output_file {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'java -jar core/lib/yuicompressor-2.4.8.jar --type css {from} -o {to}',
    // Whether to delete asset source after compression:
    'deleteSource' => false,
    // The list of asset bundles to compress:
    'bundles' => [
        \hipanel\assets\AppAsset::class,
        \hiqdev\themes\adminlte\MainAsset::class,
    ],
    // Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class' => yii\web\AssetBundle::class,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'all-{hash}.min.js',
            'css' => 'all-{hash}.min.css',
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];
