<?php
declare(strict_types=1);
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use yii\helpers\Inflector;

class LinkSorter extends \yii\widgets\LinkSorter
{
    /**
     * @var boolean whether to show sorter button
     */
    public $show = false;

    public $uiModel;

    /**
     * @var string CSS classes, that will be applied to the container
     */
    public $containerClass = 'dropdown';

    /**
     * @var string CSS classes, that will be applied to the button
     */
    public $buttonClass = 'btn btn-sm btn-default dropdown-toggle';

    public $options = ['class' => 'dropdown-menu', 'role' => 'menu', 'aria-labelledby' => ''];

    public $dataProvider;

    public function run()
    {
        if ($this->show) {
            $this->addSortClassToOptions();
            parent::run();
        }
    }

    protected function renderSortLinks(): string
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = [];
        foreach ($attributes as $name) {
            $links[] = $this->sort->link($name);
        }

        return $this->render('LinkSorterView', [
            'id' => $this->id,
            'links' => $links,
            'label' => $this->getSortLabel($this->uiModel->sort),
            'options' => array_merge($this->options, ['encode' => false]),
            'containerClass' => $this->containerClass,
            'buttonClass' => $this->buttonClass,
        ]);
    }

    private function getSortLabel(?string $attribute): ?string
    {
        $label = null;
        if (isset($this->options['label'])) {
            $label = $this->options['label'];
            unset($this->options['label']);
        } else if ($attribute) {
            if (isset($this->sort->attributes[$attribute]['label'])) {
                $label = $this->sort->attributes[$attribute]['label'];
            } elseif ($this->sort->modelClass !== null) {
                $modelClass = $this->sort->modelClass;
                /** @var \yii\base\Model $model */
                $model = $modelClass::instance();
                $label = $model->getAttributeLabel($attribute);
            } else {
                $label = Inflector::camel2words($attribute);
            }
        }

        return $label;
    }

    public function addSortClassToOptions(): void
    {
        if ($this->uiModel->sort && ($direction = $this->sort->getAttributeOrder(ltrim($this->uiModel->sort, '+-'))) !== null) {
            $class = $direction === SORT_DESC ? 'desc' : 'asc';
            if (isset($this->options['label-class'])) {
                $this->options['label-class'] .= ' ' . $class;
            } else {
                $this->options['label-class'] = $class;
            }
        }
    }
}
