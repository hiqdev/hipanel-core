<?php

namespace hipanel\widgets;

use hiqdev\combo\Combo;
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveField;

class AdvancedSearchActiveField extends ActiveField
{
    /**
     * {@inheritdoc}
     */
    private $_inputId;

    public function widget($class, $config = [])
    {
        /* @var $class \yii\base\Widget */
        $config['class'] = $class;
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        $widget = Yii::createObject($config);

        if ($widget instanceof Combo) {
            $this->_inputId = $this->_inputId ? : ($this->getInputId() . '-' . mt_rand());
            $widget->inputOptions['id'] = $this->getInputId();
        }

        $this->parts['{input}'] = $widget->run();

        return $this;
    }

    protected function getInputId() {
        return $this->_inputId ?: parent::getInputId();
    }
}