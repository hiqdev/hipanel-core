<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class AmountWithCurrency extends InputWidget
{
    public static $widgetClass = 'amount-with-currency-widget';

    /**
     * @var string Set this value to use it instead of model currency value
     */
    public $selectedCurrencyCode;
    public $currencyAttributeName;
    public $currencyAttributeOptions;
    /**
     * @var array The following keys are used:
     *
     * - `readonly`: protects currency input from changes
     * - `hidden`: hides the dropdown button and leave only currency symbol
     */
    public $currencyDropdownOptions = [];

    public function init()
    {
        parent::init();

        $this->options = array_merge(['class' => 'form-control'], $this->options);
    }

    public function run()
    {
        $this->initClientScript();

        return $this->render((new \ReflectionClass($this))->getShortName(), [
            'containerClass' => self::$widgetClass,

            'form' => $this->field->form,
            'model' => $this->model,
            'attribute' => $this->attribute,

            'selectedCurrencyCode' => $this->getSelectedCurrencyCode(),
            'currencyAttributeOptions' => $this->currencyAttributeOptions,
            'currencyDropdownOptions' => $this->currencyDropdownOptions,
        ]);
    }

    public function initClientScript()
    {
        $widgetClass = self::$widgetClass;
        $this->getView()->registerJs(<<<"JS"
        $(document).on('click', '.{$widgetClass} a', function(e) {
            var item = $(this);
            item.parents('.amount-with-currency-widget').find('.iwd-label').text(item.data('label'));
            item.parents('.amount-with-currency-widget').find(':hidden').val(item.data('value')).trigger('change');
        });
JS
        );
    }

    private function getSelectedCurrencyCode()
    {
        return $this->selectedCurrencyCode ?: Html::getAttributeValue($this->model, $this->currencyAttributeName);
    }
}
