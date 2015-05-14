<?php

namespace hipanel\widgets;

use yii\helpers\ArrayHelper;

/**
 * Class Reseller
 */
class DefaultCombo2 extends Combo2Config
{
    public $type = 'default';

    /**
     * @param array $config
     * @return array
     */
    public function getConfig($config = [])
    {
        return ArrayHelper::merge([
            'name'          => $this->type,
            'type'          => $this->type,
            'pluginOptions' => [
                'width'       => '100%',
                'placeholder' => \Yii::t('app', 'Start typing here'),
                'ajax'        => true,
            ]
        ], $config);
    }
}

