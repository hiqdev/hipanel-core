<?php

namespace hipanel\widgets;

use yii\widgets\Menu;
use yii\base\Widget;
use yii\helpers\Html;

class ActionsDropdown extends Widget
{
    public $icon = '<i class="fa fa-ellipsis-v"></i>';

    public $model;

    public $items;

    public function run()
    {
        $html = Html::beginTag('div', ['class' => 'dropdown']);
        $html .= Html::a($this->icon, '#', ['data-toggle' => 'dropdown']);
        $html .= Menu::widget([
            'items' => $this->items,
            'options' => [
                'class' => 'dropdown-menu',
            ]
        ]);
        $html .= Html::endTag('div');

        return $html;
    }

    protected function prepareItems()
    {

    }
}
