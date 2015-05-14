<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

class ComboAsset extends \yii\web\AssetBundle {
    public $sourcePath = '@frontend/assets';

    public $js = [
        'combo/combo.js'
    ];

    public $depends = [
        'hipanel\widgets\Select2Asset'
    ];
}
