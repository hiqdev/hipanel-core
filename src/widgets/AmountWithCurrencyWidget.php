<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

class AmountWithCurrencyWidget extends \yii\base\Widget
{
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_NUMBER = 'number';

    /**
     * @var
     */
    public $model;

    /**
     * @var
     */
    public $form;

    /**
     * @var
     */
    public $attribute;

    /**
     * @var
     */
    public $inputOptions;

    /**
     * @var
     */
    public $inputAttributeType;

    /**
     * @var
     */
    public $selectAttribute;

    /**
     * @var
     */
    public $selectAttributeValue;

    /**
     * @var array
     */
    public $selectAttributeOptions;

    public function init()
    {
        if ($this->inputAttributeType === null) {
            $this->inputAttributeType = self::INPUT_TYPE_TEXT;
        }
    }

    public function run()
    {
        return $this->render((new \ReflectionClass($this))->getShortName(), [
            'widgetId' => mt_rand(),
            'model' => $this->model,

            'attribute' => $this->attribute,
            'inputAttributeType' => $this->inputAttributeType,
            'inputOptions' => $this->inputOptions,

            'selectAttribute' => $this->selectAttribute,
            'selectAttributeOptions' => $this->selectAttributeOptions,
            'selectAttributeValue' => $this->selectAttributeValue,
        ]);
    }
}
