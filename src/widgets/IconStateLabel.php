<?php

namespace hipanel\widgets;

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

/**
 *
 * @property integer $size
 * @property string[] $color
 * @property string[] $icon
 * @property bool $state
 * @property string[] $message
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
     * If the array is passed, then the first parameter will be selected for the true state,
     * and the second parameter will be selected for the false state. If the string is passed,
     * then attribute will be in only one state for both options.
     *
     * @var string[]
     */
    public $icons;

    /**
     * If the array is passed, then the first parameter will be selected for the true state,
     * and the second parameter will be selected for the false state. If the string is passed,
     * then attribute will be in only one state for both options.
     *
     * @var string[]
     */
    public $colors = [
        '#008D50',
        '#bdbdbd',
    ];

    /**
     * If the array is passed, then the first parameter will be selected for the true state,
     * and the second parameter will be selected for the false state. If the string is passed,
     * then attribute will be in only one state for both options.
     *
     * @var string[]
     */
    public $messages;

    /**
     * Icon font size in `px`
     *
     * @var int
     */
    public $size = 18;

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
        return sprintf('font-size: %dpx;', $this->size);
    }

    public function getMessage(): string
    {
        return $this->variate($this->messages);
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

    private function variate($variants): string
    {
        if (is_array($variants) && count($variants) > 1) {
            return $this->getState() ? $variants[0] : $variants[1];
        }

        return $variants;
    }
}
