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

class AmountWithCurrency extends \yii\base\Widget
{
    public static $widgetClass = 'amount-with-currency-widget';

    public $currencyAttributeName;
    public $currencyAttributeOptions;
    public $model;
    public $form;
    public $attribute;
    public $inputOptions;

    public function init()
    {
        parent::init();

        $this->inputOptions = array_merge(['class' => 'form-control'], $this->inputOptions);
    }

    public function run()
    {
        $this->initClientScript();

        return $this->render((new \ReflectionClass($this))->getShortName(), [
            'form' => $this->form,
            'model' => $this->model,
            'currencyAttributeName' => $this->currencyAttributeName,
            'currencyAttributeOptions' => $this->currencyAttributeOptions,
            'attribute' => $this->attribute,
            'containerClass' => self::$widgetClass,
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
}
