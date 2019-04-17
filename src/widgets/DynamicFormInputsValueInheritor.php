<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use yii\base\Widget;
use yii\helpers\Json;

/**
 * Class DynamicFormInputsValueInheritor copies value from the latest input group
 * to the newly create one.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class DynamicFormInputsValueInheritor extends Widget
{
    /**
     * @var string
     */
    public $containerSelector = '.dynamicform_wrapper';

    public $itemSelector = '.item';

    public $inputSelectors = [];

    public function run()
    {
        $this->registerClientScript();
    }

    protected function registerClientScript()
    {
        $containerSelector = Json::htmlEncode($this->containerSelector);
        $inputSelectors = Json::htmlEncode($this->inputSelectors);
        $itemSelector = Json::htmlEncode($this->itemSelector);

        $this->view->registerJs(<<<JS
            $({$containerSelector}).on('afterInsert', function (event, newItem) {
                newItem = $(newItem);
                var templateItem = $(event.target).find($itemSelector).slice(-2, -1);
                
                $({$inputSelectors}).each(function (index, selector) {
                    var templateInput = templateItem.find(selector);
                    var newInput = newItem.find(selector)
                    
                    newInput.val(templateInput.val());
                });
            });
JS
        );
    }
}
