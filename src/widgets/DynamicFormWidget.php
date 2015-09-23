<?php

namespace hipanel\widgets;

class DynamicFormWidget extends \wbraganca\dynamicform\DynamicFormWidget {

    public function registerAssets() {
        parent::registerAssets();
        $this->view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterInsert', function(e, item) {
                var options = eval($(this).data('dynamicform'));
                var combos = $(item).find('[data-combo-field]');
                if (combos.length > 0) {
                    combos.each(function() {
                        var comboItem = this;
                        var template = $('.' + options.widgetContainer).find(options.widgetItem).first().find('[data-combo-field]').filter(function () {
                            return $(this).data('combo-field') == $(comboItem).data('combo-field');
                        });

                        if (template.length == 0) {
                            return true;
                        }

                        var config_id = $(template[0]).data('field').id;
                        $(item).closest(options.widgetItem).combo().register($(this), config_id);
                    });
                }
            });
JS
        );
    }
}