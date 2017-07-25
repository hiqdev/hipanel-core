<?php

namespace hipanel\widgets;

use yii\base\Widget;
use yii\helpers\Json;

/**
 * Class BulkAssignmentFieldsLinker registers JS to link
 * inputs in bulk assignment form in order to copy value
 * from the first input to all the inputs bellow.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class BulkAssignmentFieldsLinker extends Widget
{
    public $inputSelectors = [];

    public function run()
    {
        foreach ($this->inputSelectors as $selector) {
            $this->registerClientScriptFor($selector);
        }
    }

    protected function registerClientScriptFor($selector)
    {
        $selector = Json::htmlEncode($selector);

        $this->view->registerJs(<<<JS
            $({$selector}).on('change', function (event) {
                var similar = $(this).closest('form').find($selector),
                    value = $(this).val();

                if (this !== similar[0]) {
                    return;
                }

                if ($(this).data('field')) {
                    value = $(this).data('field').getData();
                    var isCombo = true;
                }

                similar.slice(1).each(function() {
                    if (isCombo) {
                        $(this).data('field').setData(value, true);
                    } else {
                        $(this).val(value).trigger('change');
                    }
                });
            });
JS
        );
    }
}
