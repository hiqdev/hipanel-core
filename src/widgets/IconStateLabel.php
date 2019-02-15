<?php

namespace hipanel\widgets;

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

/**
 *
 * @property array $colors
 * @property string|array $icons
 * @property string|array $messages
 */
class IconStateLabel extends Widget
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var string|array
     */
    public $icons;

    /**
     * @var array
     */
    public $colors = [
        '#00c853',
        '#bdbdbd',
    ];

    /**
     * @var string|array
     */
    public $messages;

    private function variate($variants): string
    {
        if (is_array($variants) && count($variants) > 1) {
            return $this->getState() ? $variants[0] : $variants[1];
        }

        return $variants;
    }

    protected function renderState(): string
    {
        return Html::tag('i', null, [
            'aria-hidden' => 'true',
            'class' => implode(' ', [$this->getIcon()]),
            'style' => implode(' ', [$this->getColor(), $this->getSize()]),
            'title' => $this->getMessage(),
        ]);
    }

    public function run(): string
    {
        return $this->renderState();
    }

    public function getState(): bool
    {
        return (bool)$this->model->{$this->attribute};
    }

    public function getIcon(): string
    {
        return sprintf('fa %s fw', $this->variate($this->icons));
    }

    public function getColor(): string
    {
        return sprintf('color: %s;', $this->variate($this->colors));
    }

    public function getSize(): string
    {
        return 'font-size: 18px;';
    }

    public function getMessage(): string
    {
        return $this->variate($this->messages);
    }
}

