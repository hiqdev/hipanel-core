<?php

namespace hipanel\widgets\combo;

use hipanel\widgets\Combo;
use yii\helpers\ArrayHelper;

/**
 * Class Default
 */
class StaticCombo extends Combo
{
    public $type = 'default';

    public $data = [];

    public function getPluginOptions($options = []) {
        return parent::getPluginOptions([
            'hasId'          => false,
            'select2Options' => [
                'ajax' => false,
                'data' => $this->data
            ]
        ]);
    }
}

