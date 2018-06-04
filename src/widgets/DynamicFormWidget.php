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


use yii\web\View;

class DynamicFormWidget extends \wbraganca\dynamicform\DynamicFormWidget
{
    /**
     * @param View $view
     */
    public function registerAssets($view)
    {
        parent::registerAssets($view);
        // For init select2
        $view->registerJs(<<<JS
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
        // For init datetime picker
        $view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterInsert', function(e, item) {
                var options = eval($(this).data('dynamicform'));
                var pickers = $(item).find('[data-krajee-datetimepicker]');
                if (pickers.length > 0) {
                    pickers.each(function() {
                        var pickerItem = this;
                        var template = $('.' + options.widgetContainer).find(options.widgetItem).first().find('[data-krajee-datetimepicker]').filter(function () {
                            return $(this).data('krajee-datetimepicker') === $(pickerItem).data('krajee-datetimepicker');
                        });

                        if (template.length == 0) {
                            return true;
                        }

                        var config_id = $(template[0]).data('krajee-datetimepicker');
                        var configObj = window[config_id];
                        var elementId = $(pickerItem).attr('id');
                        if (configObj !== null && typeof configObj === 'object') {
                            $('#' + elementId + '-datetime').datetimepicker(configObj);
                        } else {
                            $('#' + elementId + '-datetime').datetimepicker();
                        }
                    });
                }
            });
JS
        );
        // For init datetime picker
        $view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterInsert', function(e, item) {
                var options = eval($(this).data('dynamicform'));
                var pickers = $(item).find('[data-hiqdev-datetimepicker]');
                if (pickers.length > 0) {
                    pickers.each(function() {
                        var pickerItem = this;
                        var template = $('.' + options.widgetContainer).find(options.widgetItem).first().find('[data-hiqdev-datetimepicker]').filter(function () {
                            return $(this).data('hiqdev-datetimepicker') === $(pickerItem).data('hiqdev-datetimepicker');
                        });

                        if (template.length == 0) {
                            return true;
                        }

                        var config_id = $(template[0]).data('hiqdev-datetimepicker');
                        var configObj = window.hiqdev_datetimepicker_options[config_id];
                        var elementId = $(pickerItem).attr('id');
                        if (configObj !== null && typeof configObj === 'object') {
                            $('#' + elementId).parent().datetimepicker(configObj);
                        } else {
                            $('#' + elementId).parent().datetimepicker();
                        }
                    });
                }
            });
JS
        );
        // For PasswordInput
        $view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterInsert', function(e, item) {
                var passwordInputs = $(item).find('input[type=password]').closest('div.input-group');
                if (passwordInputs.length > 0) {
                    passwordInputs.filter(function () {
                        return $(this).find('.show-password').length > 0;
                    }).each(function() {
                        if ($.isFunction($.fn.passwordInput)) {
                            $(this).passwordInput(); 
                        }
                    });
                }
            });
JS
        );
    }
}
