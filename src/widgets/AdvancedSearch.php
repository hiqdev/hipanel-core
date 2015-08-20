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
     * @var ActiveForm form to be used
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
            'id'    => $this->divId(),
            'class' => 'row',
            'style' => 'margin-bottom: 20px;' . $display_none,
        ]);
        $this->_form = ActiveForm::begin([
            'id'        => 'form-' . $this->divId(),
            'action'    => $this->action,
            'method'    => $this->method,
            'options'   => $this->options,
        ]);
        echo Html::hiddenInput(sprintf('%s[search_form]', $this->model->formName()), 1);
    }

    static public function renderButton()
    {
        return Html::a(Yii::t('app', 'Advanced search'), '#', ['class' => 'btn btn-info', 'id' => 'advancedsearch-button']);
    }

    public function run()
    {
        echo Html::beginTag('div', ['class' => 'col-md-12']);
            echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info']);
            echo ' &nbsp; ';
            echo Html::a(Yii::t('app', 'Clear'), $this->action, ['class' => 'btn btn-default']);
        echo Html::endTag('div');
        $this->_form->end();
        echo Html::endTag('div');
    }

    public function field($attribute, $options = [])
    {
        return $this->_form->field($this->model, $attribute, $options);
    }

    public function registerMyJs()
    {
        $div_id = $this->divId();
        Yii::$app->getView()->registerJs(new JsExpression(<<<JS
$('#advancedsearch-button').click(function () {
    $('#${div_id}').toggle();
    return false;
});
$('#search-form-ticket-pjax').on('pjax:end', function () {
    $.pjax.reload({container:'#ticket-grid-pjax', timeout: false});
});
JS
        ), \yii\web\View::POS_READY);
    }

    public function divId()
    {
        return 'advancedsearch-'.Inflector::camel2id($this->model->formName());
    }
}
