<?php

namespace hipanel\widgets;

use yii\helpers\Html;

class LinkSorter extends \yii\widgets\LinkSorter
{
    public $show = false;

    public $containerClass = 'dropdown';

    public $buttonClass = 'btn btn-default dropdown-toggle';

    public $options = ['class' => 'dropdown-menu', 'role' => 'menu', 'aria-labelledby' => ''];

    public function run()
    {
        if ($this->show) {
            parent::run();
        }
    }

    /**
     * Renders the sort links.
     * @return string the rendering result
     */
    protected function renderSortLinks()
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = [];
        foreach ($attributes as $name) {
            $links[] = $this->sort->link($name);
        }

//        return Html::ul($links, array_merge($this->options, ['encode' => false]));
        return $this->render('LinkSorterView', [
            'id' => $this->id,
            'links' => $links,
            'attributes' => $this->sort->attributes,
            'options' => array_merge($this->options, ['encode' => false]),
            'containerClass' => $this->containerClass,
            'buttonClass' => $this->buttonClass,
        ]);
    }
}