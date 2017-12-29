<?php

namespace hipanel\widgets;

use yii\base\Widget;
use yii\bootstrap\Html;

class DynamicFormCopyButton extends Widget
{
    public $buttonOptions = ['class' => 'add-item copy btn btn-info btn-sm'];

    public $icon = '<i class="glyphicon glyphicon-duplicate"></i>';

    public $widgetContainer = '.dynamicform_wrapper';

    public function run()
    {
        $this->view->registerJs(<<<"JS"
            (function() {
                window.prevItem = null;
                var dynamicFormWrapper = $('{$this->widgetContainer}');
                dynamicFormWrapper.on('beforeInsert', function(e, item) {
                    if ($(item).hasClass('copy')) {
                        window.prevItem = $(item).closest('div.item');    
                    }
                });
                dynamicFormWrapper.on('afterInsert', function(e, item) {
                    var currentItem = $(item), prevItem = window.prevItem;
                    if (window.prevItem !== null) {
                        prevItem.find(':input').each(function() {
                            var prevInput = this;
                            if (prevInput.getAttribute("id") !== null) {
                                var needle = prevInput.getAttribute("id").split("-").slice(-1).pop();
                                var newInput = currentItem.find(":input[id$=\'" + needle + "\']");
                                if ( newInput.length === 1 ) {
                                    if (newInput.attr('data-combo-field')) { // if select2
                                        var prevOption = $(prevInput).find(':selected');
                                        if (prevOption.length) {
                                            var newOption = new Option(prevOption.text(), prevOption.attr('value'), true, true);
                                            newOption.setAttribute('data-select2-id', prevOption.attr('data-select2-id'));
                                            newInput.append(newOption).trigger('change');
                                            newInput.trigger('select2:select');
                                        }
                                    } else if (newInput.attr('data-amount-with-currency') === 'currency') { // if amount with currency
                                        newInput.val(prevInput.value);
                                        newInput.closest('.amount-with-currency-widget').find('a[data-value="' + prevInput.value + '"]').click();
                                    } else {
                                        newInput.val(prevInput.value);
                                    }
                                }
                            }
                        });
                    }
                    window.prevItem = null;
                });
            })();
JS
        );

        return Html::button($this->icon, $this->buttonOptions);
    }
}
