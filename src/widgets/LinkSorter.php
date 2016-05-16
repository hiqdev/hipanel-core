<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

class LinkSorter extends \yii\widgets\LinkSorter
{
    /**
     * @var boolean whether to show sorter button
     */
    public $show = false;

    /**
     * @var string CSS classes, that will be applied to the container
     */
    public $containerClass = 'dropdown';

    /**
     * @var string CSS classes, that will be applied to the button
     */
    public $buttonClass = 'btn btn-sm btn-default dropdown-toggle';

    public $options = ['class' => 'dropdown-menu', 'role' => 'menu', 'aria-labelledby' => ''];

    public function run()
    {
        if ($this->show) {
            parent::run();
        }
    }

    /**
     * Renders the sort links.
     *
     * @return string the rendering result
     */
    protected function renderSortLinks()
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links      = [];
        foreach ($attributes as $name) {
            $links[] = $this->sort->link($name);
        }

        return $this->render('LinkSorterView', [
            'id'             => $this->id,
            'links'          => $links,
            'attributes'     => $this->sort->attributes,
            'options'        => array_merge($this->options, ['encode' => false]),
            'containerClass' => $this->containerClass,
            'buttonClass'    => $this->buttonClass,
        ]);
    }
}
