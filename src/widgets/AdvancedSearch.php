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

use hipanel\base\Model;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveField;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\JsExpression;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
/**
 * Advanced Search widget.
 *
 * Usage:
 * ```php
 * $form = AdvancedSearch::begin(['model' => $model]);
 * echo $form->field('domain');
 * $form->end();
 * ```
 */
class AdvancedSearch extends Widget
{
    /**
     * @var
     */
    protected $_view;

    /**
     * @var Model model
     */
    public $model;

    /**
     * @var string|array action url
     */
    public $action = ['index'];

    /**
     * @var string request method
     */
    public $method = 'GET';

    /**
     * @var array options passed to ActiveForm
     */
    public $formOptions = [
        'data-pjax' => true,
    ];

    /**
     * @var array Options that will be used for the search wrapping tag.
     * The following options have special effect:
     *  - `tag`: the tag name. Defaults to `div`
     *  - `displayNone`: whether to hide the form untill special button will be pressed. Defaults to true.
     */
    public $options = [];

    /**
     * @var ActiveForm form to be used
     */
    protected $_form;

    /**
     * @var array|false Options that will be used for the form submit button wrapping tag.
     * The following options have special effect:
     *  - `tag`: the tag name. Defaults to `div`
     *
     * Setting this property to `false` will prevent submit button render.
     */
    public $submitButtonWrapperOptions = [];

    /**
     * Renders the starting div.
     */
    public function init()
    {
        $this->registerMyJs();
        $display_none = '';

        if (ArrayHelper::remove($this->options, 'displayNone', true) === true) {
            $display_none = Yii::$app->request->get($this->model->formName())['search_form'] ? '' : 'display:none';
        }

        if ($this->submitButtonWrapperOptions !== false) {
            $this->submitButtonWrapperOptions = ArrayHelper::merge([
                'class' => 'col-md-12',
            ], $this->submitButtonWrapperOptions);
        }

        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        echo Html::beginTag($tag, ArrayHelper::merge([
            'id'    => $this->getDivId(),
            'class' => 'row',
            'style' => 'margin-bottom: 1rem; margin-top: 1rem; ' . $display_none,
        ], $this->options));

        $this->_form = ActiveForm::begin([
            'id'        => 'form-' . $this->getDivId(),
            'action'    => $this->action,
            'method'    => $this->method,
            'options'   => $this->formOptions,
            'fieldClass' => AdvancedSearchActiveField::class,
        ]);
        echo Html::hiddenInput(Html::getInputName($this->model, 'search_form'), 1);
    }

    public static function renderButton()
    {
        return Html::a(Yii::t('hipanel', 'Advanced search'), '#', ['class' => 'btn btn-info btn-sm', 'id' => 'advancedsearch-button']);
    }

    public function run()
    {
        if ($this->submitButtonWrapperOptions !== false) {
            $tag = ArrayHelper::remove($this->submitButtonWrapperOptions, 'tag', 'div');
            echo Html::beginTag($tag, $this->submitButtonWrapperOptions);
            echo Html::submitButton(Yii::t('hipanel', 'Search'), ['class' => 'btn btn-sm btn-info']);
            echo ' &nbsp; ';
            echo Html::a(Yii::t('hipanel', 'Clear'), $this->action, [
                'class' => 'btn btn-sm btn-default',
                'data-params' => [
                    'clear-filters' => true,
                    Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken(),
                ],
                'data-method' => 'POST',
            ]);
            echo Html::endTag('div');
        }

        $this->_form->end();
        echo Html::endTag('div');
    }

    /**
     * @param string $attribute
     * @param array $options
     * @return ActiveField
     */
    public function field($attribute, $options = [])
    {
        return $this->_form->field($this->model, $attribute, $options)
            ->textInput(['placeholder' => $this->model->getAttributeLabel($attribute)])
            ->label(false);
    }

    public function registerMyJs()
    {
        $div_id = $this->getDivId();
        Yii::$app->getView()->registerJs(new JsExpression(<<<JS
$('#advancedsearch-button').click(function (event) {
    $('#${div_id}').toggle();
    event.preventDefault();
});
$('#search-form-ticket-pjax').on('pjax:end', function () {
    $.pjax.reload({container:'#ticket-grid-pjax', timeout: false});
});
JS
        ), \yii\web\View::POS_READY);
    }

    public function getDivId()
    {
        if ($this->getId(false) !== null) {
            $id = $this->getId(false);
        } else {
            $id = Inflector::camel2id($this->model->formName());
        }
        return 'advancedsearch-' . $id;
    }

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->_view = $view;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = Yii::$app->view;
        }
        return $this->_view;
    }
}
