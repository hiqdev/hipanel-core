<?php

namespace frontend\components\grid;

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;

class SwitchColumn extends DataColumn
{
    /** @inheritdoc */
    public $format = 'raw';

    /** @var boolean Filtering is disabled for SwitchColumn */
    public $filter = false;

    /** @var array pluginOptions for widget */
    public $pluginOptions = [];

    /** @inheritdoc */
    public $defaultOptions = [
//        'headerOptions' => [
//            'style' => 'width:1em !important',
//        ],
        'pluginOptions'         => [
            'size'      => 'mini',
        ],
    ];

    public function getDataCellValue ($model, $key, $index) {
        return SwitchInput::widget([
            'name'          => 'swc'.$key.$model->id,
            'pluginOptions' => ArrayHelper::merge($this->pluginOptions,[
                'state'             => (boolean)parent::getDataCellValue($model,$key,$index),
                'onSwitchChange'    => new JsExpression('function () { console.log("hello"); }'),
            ]),
        ]);
    }
}
