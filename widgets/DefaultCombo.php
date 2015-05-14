<?php

namespace hipanel\widgets;

use yii\helpers\ArrayHelper;

/**
 * Class Default
 */
class DefaultCombo extends Combo
{
    public $type = 'default';

    public $_pluginOptions = [
        'hasId'          => false,
        'select2Options' => [
            'ajax' => false,
        ]
    ];
}

