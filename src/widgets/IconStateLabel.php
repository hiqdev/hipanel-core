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

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * @property integer $size
 * @property string[] $color
 * @property string[] $icon
 * @property bool $state
 * @property string[] $message
 */
class IconStateLabel extends Widget
{
    use NoWidgetEventTrait;

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
     * Icon font size in `px`.
     *
     * @var int
     */
    public int $size = 18;

    /**
     * @var array of CSS styles, for example: ['width' => '100px', 'display' => 'flex', ...]
     */
    public array $cssStyles = [];

    public function run(): string
    {
        return $this->renderState();
    }

    protected function getState(): bool
    {
        return (bool)$this->model->{$this->attribute};
    }

    protected function getIcon(): string
    {
        return sprintf('fa %s fw', $this->variate($this->icons));
    }

    protected function getColor(): array
    {
        return ['color' => $this->variate($this->colors)];
    }

    protected function getSize(): array
    {
        return ['font-size' => sprintf('%dpx', $this->size)];
    }

    protected function getMessage(): string
    {
        return $this->variate($this->messages);
    }

    protected function renderState(): string
    {
        return Html::tag('i', Html::tag('span', Html::encode($this->getMessage()), ['class' => 'sr-only']), [
            'aria-hidden' => 'true',
            'class' => implode(' ', [$this->getIcon()]),
            'style' => array_merge($this->getColor(), $this->getSize(), $this->cssStyles),
            'title' => Html::encode($this->getMessage()),
        ]);
    }

    private function variate($variants): string
    {
        if (!is_array($variants)) {
            $variants = [$variants];
        }

        $res = (count($variants) > 1 && !$this->getState())
                    ? $variants[1]
                    : $variants[2];

        return Html::encode($res);
    }
}
