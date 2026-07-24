<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\modules\finance\widgets\BillTypeVueTreeSelect;
use yii\web\View;

class DynamicFormWidget extends \wbraganca\dynamicform\DynamicFormWidget
{
    /**
     * @param View $view
     */
    public function registerAssets($view)
    {
        parent::registerAssets($view);
        // ObjectSelector fix, rebind events
        $view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterDelete', function(e) {
                var objectSelectorInputs = $(this).find('[data-object-selector-field]');
                objectSelectorInputs.each(function(i, elem) {
                    var elem = $(elem), changer = elem.prev('select');
                    var objectInputId = elem.attr('id');
                    var changerInputId = changer.attr('id');
                    changer.off();
                    initObjectSelectorChanger(changerInputId, objectInputId);
                });
            });
JS
        );
        // For init Destination Region Inputs
        $view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterInsert', function(e, item) {
                var destinationRegionInputs = $(item).find('[data-destination-field]');
                if (destinationRegionInputs.length) {
                    destinationRegionInputs.each(function() {
                        if (typeof tryToResolveDestinationRange === 'function') {
                            $(this).on('select2:selecting', tryToResolveDestinationRange);
                        }
                    });
                }
            });
JS
        );
        // For init select2
        $view->registerJs(<<<JS
            $('.{$this->widgetContainer}').on('afterInsert afterDelete', function(e, item) {
                var options = eval($(this).data('dynamicform'));
                var combos = item ? $(item).find('[data-combo-field]') : $(this).find('[data-combo-field]');
                if (combos.length > 0) {
                    combos.each(function() {
                        var comboItem = this;
                        if (e.type === 'afterDelete' && typeof $(comboItem).data('select2') === 'object') {
                            $(comboItem).select2('destroy');
                            comboItem.dataset.select2Id = this.getAttribute('id');
                        }
                        var templates = $('.' + options.widgetContainer).find(options.widgetItem).first().find('[data-combo-field]').filter(function () {
                            return $(this).data('combo-field') === $(comboItem).data('combo-field');
                        });
                        if (templates.length === 0) {
                            return true;
                        }
                        var template = templates.length === 1 ? $(templates[0]) : $(templates.filter((idx, entry) => {
                          return entry.id.split('-').slice(-1)[0] === comboItem.id.split('-').slice(-1)[0];
                        }));
                        if (template.data('field')) {
                            var config_id = template.data('field').id;
                            if (item) {
                                $(item).closest(options.widgetItem).combo().register($(this), config_id);
                            } else {
                                $(comboItem).closest('form').combo().register($(comboItem), config_id);
                            }
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
                            $('#' + elementId).flatpickr(configObj);
                        } else {
                            console.error('config_id ' + config_id + ' not found');
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
        // For VueTreeSelect
        $mixin = BillTypeVueTreeSelect::mixin();
        $view->registerJs(
            sprintf(/** @lang JavaScript */ "
              (() => {
                const treeSelectHandler = function (e, item) {
                  const treeSelectInputs = $(item).find('treeselect');
                  const prevAdjusment = $(item).prev().find('[id*=type_id]');
                  if (treeSelectInputs.length > 0 && typeof window.Vue !== 'undefined') {
                    treeSelectInputs.each(function () {
                      const container = $(this).parents('div').eq(0);
                      const mixin = $mixin;
                      new Vue({
                        el: container.get(0),
                        mixins: [mixin],
                        mounted() {
                          this.value = prevAdjusment.val();
                          if (prevAdjusment.data('adjustment') === 'yes') {
                            this.adjustmentOnly = true;
                            this.toggleOptions(true);
                          }
                        },
                      });
                    });
                  }
                };
                const containerSelector = $('.%s');
                containerSelector.on('afterInsert', treeSelectHandler);
                containerSelector.parents('div[class*=_dynamicform_wrapper]').on('afterInsert', function (e, item) {
                  $(item).find('div[class*=_dynamicform_wrapper]').on('afterInsert', treeSelectHandler);
                });
              })();
            ",
                $this->widgetContainer)
        );
    }
}
