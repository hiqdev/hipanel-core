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

class AmountWithCurrency extends \yii\base\Widget
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
    public $inputOptions = [];

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
    public $selectAttributeOptions = [];

    public function init()
    {
        if ($this->inputAttributeType === null) {
            $this->inputAttributeType = self::INPUT_TYPE_TEXT;
        }
    }

    public function run()
    {
        $this->initClientScript();

        return $this->render((new \ReflectionClass($this))->getShortName(), [
            'model' => $this->model,

            'attribute' => $this->attribute,
            'inputAttributeType' => $this->inputAttributeType,
            'inputOptions' => $this->inputOptions,

            'selectAttribute' => $this->selectAttribute,
            'selectAttributeOptions' => $this->selectAttributeOptions,
            'selectAttributeValue' => $this->selectAttributeValue,
        ]);
    }

    public function initClientScript()
    {
        $this->getView()->registerJs(<<<'JS'
        $(document).on('click', '.amount-with-currency-widget a', function(e) {
            var $item = $(this);
            $item.parents('.amount-with-currency-widget').find('.iwd-label').text($item.data('label'));
            $item.parents('.amount-with-currency-widget').find(':hidden').val($item.data('value'));
        });
JS
        );
    }
}
