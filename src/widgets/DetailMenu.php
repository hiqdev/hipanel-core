<?php

namespace hipanel\widgets;

use yii\base\Widget;
use yii\widgets\Menu;

class DetailMenu extends Widget
{
    public $options = [];

    public $items = [];

    public $model;

    public $menuClass = Menu::class;

    public function run()
    {
        $class = $this->menuClass;
        return $class::widget([
            'items' => $this->items,
            'options' => array_merge(['class' => 'nav'], $this->options),
        ]);
    }
}
