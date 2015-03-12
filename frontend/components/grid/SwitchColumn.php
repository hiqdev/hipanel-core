<?php

namespace frontend\components\grid;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;

class SwitchColumn extends DataColumn
{
    /**
     * @var array
     */
    public $format = 'raw';

    /**
     * @var array
     */
    public $pluginOptions = [];

    public function getDataCellValue ($model, $key, $index) {
        $options = ArrayHelper::merge($this->pluginOptions,[
            'state'             => (boolean)parent::getDataCellValue($model,$key,$index),
            'onSwitchChange'    => new JsExpression('function () { console.log("hello"); }'),
        ]);
        return SwitchInput::widget([
            'name'          => 'swc'.$key.$model->id,
            'pluginOptions' => $options,
        ]);
    }
}
