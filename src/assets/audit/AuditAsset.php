<?php

declare(strict_types=1);

namespace hipanel\assets\audit;

use hipanel\assets\MixAssetBundle;

class AuditAsset extends MixAssetBundle
{
    public $sourcePath = __DIR__ . '/build';
    public $baseUrl = '/audit/index';
    public $publishOptions = ['forceCopy' => true];
}
