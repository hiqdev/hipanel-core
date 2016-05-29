<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class SwitchColumn extends DataColumn
{
    /** {@inheritdoc} */
    public $format = 'raw';

    /** @var boolean Filtering is disabled for SwitchColumn */
    public $filter = false;

    /** @var array pluginOptions for widget */
    public $pluginOptions = [];

    /** {@inheritdoc} */
    public $defaultOptions = [
//        'headerOptions' => [
//            'style' => 'width:1em !important',
//        ],
        'pluginOptions'         => [
            'size'      => 'mini',
        ],
    ];

    public function getDataCellValue($model, $key, $index)
    {
        return SwitchInput::widget([
            'name'          => 'swc' . $key . $model->id,
            'pluginOptions' => ArrayHelper::merge($this->pluginOptions, [
                'state'             => (bool) parent::getDataCellValue($model, $key, $index),
                'onSwitchChange'    => new JsExpression('function () { console.log("hello"); }'),
            ]),
        ]);
    }
}
