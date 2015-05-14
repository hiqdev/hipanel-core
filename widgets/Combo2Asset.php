<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

class Combo2Asset extends \yii\web\AssetBundle {
    public $sourcePath = '@frontend/assets';

    public $js = [
        'combo2/combo2.js'
    ];

    public $depends = [
        'frontend\assets\Select2Asset'
    ];
}
