<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\assets\CheckboxesAsset;
use hiqdev\higrid\FeaturedColumnTrait;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    use FeaturedColumnTrait {
        registerClientScript as traitRegisterClientScript;
    }

    public $attribute;

    public function registerClientScript()
    {
        parent::registerClientScript();
        $this->traitRegisterClientScript();
        $this->registerCheckboxesScript();
    }

    /** {@inheritdoc} */
    public $defaultOptions = [
        'headerOptions' => [
            'style' => 'text-align: center; vertical-align: middle; width: 1em;',
        ],
        'contentOptions' => [
            'style' => 'text-align: center; vertical-align: middle;',
        ],
        'checkboxOptions' => [
            'class' => 'grid-checkbox option-input',
        ],
    ];

    private function registerCheckboxesScript()
    {
        CheckboxesAsset::register($this->grid->getView());
        $id = $this->grid->options['id'];

        $this->grid->getView()->registerJs("jQuery('#$id').checkboxes('range', true);");
    }
}
