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

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Label widget displays bootstrap colored label.
 *
 * Usage:
 * Label::widget([
 *      'color' => 'warning',
 *      'label' => Yii::t('hipanel', 'Some label'),
 *      'tag'   => 'span',
 * ]);
 *
 * @var string
 */
class Label extends \yii\base\Widget
{
    use NoWidgetEventTrait;

    public $default = 'default';

    public $noneOptions = [];

    public $labelOptions = [];

    public $addClass = '';

    /**
     * @var string
     */
    public $tag = 'span';

    /**
     * Available types: label, text.
     */
    public $type = 'label';

    /**
     * @var string
     * Because $class is used by Yii::createObject
     */
    protected $_color;

    /**
     * @var string
     */
    protected $_label;

    /*public function init()
    {
        $this->noneOptions = ArrayHelper::merge(['class' => $this->buildClass('text-muted')], $this->noneOptions);
        $this->labelOptions = ArrayHelper::merge(['class' => $this->buildClass()], $this->labelOptions);
    }*/

    public function run()
    {
        return $this->renderLabel();
    }

    protected function renderLabel()
    {
        $color = $this->getColor();
        if ($color === 'none') {
            return Html::tag('b',        $this->getLabel(), $this->buildOptions($this->noneOptions, 'text-muted'));
        } else {
            return Html::tag($this->tag, $this->getLabel(), $this->buildOptions($this->labelOptions));
        }
    }

    public function buildOptions($options, $baseClass = null)
    {
        return ArrayHelper::merge(['class' => $this->buildClass($baseClass)], $options);
    }

    public function buildClass($base = null)
    {
        if (is_null($base)) {
            $base = $this->getCssClass() . ' ' . $this->getPrefix() . '-' . $this->getColor();
        }

        return implode(' ', array_filter([$base, $this->addClass]));
    }

    public function setColor($color)
    {
        $this->_color = $color;
    }

    public function getColor()
    {
        return $this->_color ?: $this->default;
    }

    public function setLabel($label)
    {
        $this->_label = $label;
    }

    public function getLabel()
    {
        return $this->_label ?: $this->default;
    }

    public function getCssClass()
    {
        return $this->type === 'text' ? 'text-bold' : $this->type;
    }

    public function getPrefix()
    {
        return $this->type;
    }
}
