<?php

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/**
 * Advanced Search widget.
 *
 * Usage:
 * <? $form = AdvancedSearch::begin([
 *      'model' => $model,
 *      'class' => 'warning',
 *      'label' => \Yii::t('app', 'Some label'),
 *      'tag'   => 'span',
 * ]) ?>
 *      <? $form->field('domain') ?>
 * <? $form::end() ?>
 */
class AdvancedSearch extends Widget
{
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
    public $options = [
        'data-pjax' => true,
    ];

    /**
     * @var ActiveForm
     */
    protected $_form;

    /**
     * Renders the starting div
     */
    public function init()
    {
        $this->registerMyJs();
        $display_none = Yii::$app->request->get($this->model->formName())['search_form'] ? '' : 'display:none';
        echo Html::beginTag('div', [
            'class' => $this->formId() . ' row',
            'style' => 'margin-bottom: 20px;' . $display_none,
        ]);
        $this->_form = ActiveForm::begin([
            'id'        => $this->formId(),
            'action'    => $this->action,
            'method'    => $this->method,
            'options'   => $this->options,
        ]);
        echo $this->_form->field($this->model, 'search_form')->hiddenInput(['value' => 1])->label(false);
    }

    public function run()
    {
        $this->_form->end();
        echo Html::endTag('div');
    }

    public function field($attribute, $options = [])
    {
        return $this->_form->field($this->model, $attribute, $options);
    }

    public function registerMyJs()
    {
        $form_id = $this->formId();
        Yii::$app->getView()->registerJs(new JsExpression(<<<JS
$('.search-button').click(function () {
    $('.${form_id}').toggle();
    return false;
});
$('#search-form-ticket-pjax').on('pjax:end', function () {
    $.pjax.reload({container:'#ticket-grid-pjax', timeout: false});
});
JS
        ), \yii\web\View::POS_READY);
    }

    public function formId()
    {
        return Inflector::camel2id($this->model->formName());
    }
}
