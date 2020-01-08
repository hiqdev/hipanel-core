<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\widgets\SwitchInput;
use yii\helpers\ArrayHelper;

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
        'pluginOptions' => [
            'size' => 'mini',
        ],
    ];

    /**
     * @var array options that will be passed to [[SwitchInput]] widget
     */
    public $switchInputOptions = [];

    public function getDataCellValue($model, $key, $index)
    {
        return SwitchInput::widget([
            'name' => 'swc' . $key . $model->id,
            'clientOptions' => ArrayHelper::merge([
                'state' => (bool) parent::getDataCellValue($model, $key, $index),
            ], $this->pluginOptions, $this->switchInputOptions),
        ]);
    }
}
