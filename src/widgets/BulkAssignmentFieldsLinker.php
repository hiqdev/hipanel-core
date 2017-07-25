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
            $({$selector}).on('change keyup', function (event) {
                var input = $(this),
                    similar = input.closest('form').find($selector),
                    value = input.val();

                if (this !== similar[0]) {
                    if (event.namespace !== 'bulky') input.data('wasChanged', true);
                    return true;
                }

                if (input.data('field')) {
                    value = input.data('field').getData();
                    var isCombo = true;
                }

                similar.slice(1).each(function() {
                    if ($(this).data('wasChanged')) {
                        return true;
                    }

                    if (isCombo) {
                        $(this).data('field').setData(value, true);
                    } else {
                        $(this).val(value).trigger('change.bulky');
                    }
                });
            });
JS
        );
    }
}
