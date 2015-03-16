<?php

namespace frontend\components\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Label widget displays bootstrap colored label
 *
 * Usage:
 * Label::widget([
 *      'class' => 'warning',
 *      'label' => \Yii::t('app', 'Some label'),
 *      'tag'   => 'span',
 * ]);
 *
 * Class Label
 */
class Label extends Widget
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     * Because $class is used by Yii::createObject
     */
    public $zclass;

    /**
     * @var string
     */
    public $tag = 'span';

    static public function widget ($config = []) {
        if (!$config['zclass']) $config['zclass'] = $config['class'];
        return parent::widget($config);
    }

    public function run () {
        parent::run();
        $this->renderLabel();
    }

    private function renderLabel () {
        print Html::tag($this->tag, $this->label, ['class' => "label label-".$this->getZclass()]);
    }

    protected function getZclass () {
        return $this->zclass ?: 'default';
    }
}
