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

use hipanel\helpers\ArrayHelper;
use hiqdev\assets\autosize\AutosizeAsset;
use hiqdev\combo\Combo;
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveField;

/**
 * Class AdvancedSearchActiveField.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
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
            $this->_inputId = $this->_inputId ?: ($this->getInputId() . '-' . mt_rand());
            $widget->inputOptions['id'] = $this->getInputId();
            $widget->setPluginOptions(ArrayHelper::merge($widget->pluginOptions, [
                'select2Options' => [
                    'placeholder' => $this->model->getAttributeLabel($this->attribute),
                ],
            ]));
        }
        $this->parts['{input}'] = $widget->run();

        return $this;
    }

    public function textarea($options = [])
    {
        AutosizeAsset::activate($this->form->getView(), '[data-autosize]');

        return parent::textarea(ArrayHelper::merge([
            'data-autosize' => true, 'rows' => 1,
            'placeholder' => $this->model->getAttributeLabel($this->attribute),
            'style' => ['max-height' => '30vh'],
        ], $options));
    }

    protected function getInputId()
    {
        return $this->_inputId ?: parent::getInputId();
    }

    /**
     * Renders the opening tag of the field container.
     * @return string the rendering result
     */
    public function begin()
    {
        if ($this->form->enableClientScript) {
            $clientOptions = $this->getClientOptions();
            if (!empty($clientOptions)) {
                $this->form->attributes[] = $clientOptions;
            }
        }

        $inputID = $this->getInputId();
        $attribute = Html::getAttributeName($this->attribute);
        $options = $this->options;
        $class = isset($options['class']) ? [$options['class']] : [];
        $class[] = "field-$inputID";
        if ($this->model->isAttributeRequired($attribute)) {
            $class[] = $this->form->requiredCssClass;
        }
        if ($this->model->hasErrors($attribute)) {
            $class[] = $this->form->errorCssClass;
        }
        $options['class'] = implode(' ', $class);

        $tag = ArrayHelper::remove($options, 'tag', 'div');
        // Added tooltip help
        $options['data'] = [
            'toggle' => 'tooltip',
            'title' => $this->model->getAttributeLabel($this->attribute),
        ];

        return Html::beginTag($tag, $options);
    }
}
