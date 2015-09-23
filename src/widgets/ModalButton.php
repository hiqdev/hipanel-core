<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\Modal;
use Yii;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class ModalButton
 *
 * Renders [[Modal]] widget in form with custom toggle button.
 *
 * @package hipanel\widgets
 */
class ModalButton extends Widget
{
    /**
     * Toggle button will be placed outside of the form
     */
    const BUTTON_OUTSIDE = 1;

    /**
     * Toggle button will be rendered inside of the form with [[Modal]] widget
     */
    const BUTTON_IN_MODAL = 2;

    /**
     * Submit with HTML POST request
     */
    const SUBMIT_HTML = 0;

    /**
     * Submit using PJAX
     */
    const SUBMIT_PJAX = 1;

    /**
     * Submit using AJAX
     */
    const SUBMIT_AJAX = 2;

    /**
     * @var ActiveRecord the model. Is required.
     */
    public $model;

    /**
     * @var string Used to generate form action URL and HTML id of modal.
     * May be set manually, otherwise will be extracted from the model
     */
    public $scenario;

    /**
     * @var string Model scenario before widget run
     */
    protected $_oldScenario;

    /**
     * @var array|ActiveForm The options for the HTML form.
     * The following special options are supported:
     *
     * - action: string|array, the action, that will be passed as first argument to [[Html::beginForm()]]
     * - method: string, the method, that will be passed as second argument to [[Html::beginForm()]]
     *
     * The rest of the options will be passed to the [[ActiveForm::begin()]] method
     *
     * If the property was not false, it will contain [[ActiveForm]] instance after [[ModalButton::begin()]].
     */
    public $form = [];

    /**
     * @var array The options for rendering toggle button
     * The toggle button is used to toggle the visibility of the modal window.
     * If this property is false, no toggle button will be rendered.
     *
     * The following special options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to 'Show'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     */
    public $button = [];

    /**
     * @var string|callable The body of modal window.
     * If callable - will get the only argument - [[$this->model]]
     */
    public $body;

    /**
     * @var array HTML options that will be passed to the [[Modal]] widget
     * When ```footer``` is array, the following special options are supported:
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to 'Show'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     *
     */
    public $modal = [];

    /**
     * @var integer determines the way of form submit.
     * @see [[SUBMIT_HTML]]
     * @see [[SUBMIT_PJAX]]
     * @see [[SUBMIT_AJAX]]
     */
    public $submit = self::SUBMIT_PJAX;

    /**
     * @var array options that will be passed to ajax submit JS
     * @see [[registerAjaxSubmit]]
     */
    public $ajaxOptions = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->initOptions();

        if ($this->button['position'] === static::BUTTON_OUTSIDE && isset($this->button['label'])) {
            $this->renderButton();
        }

        if ($this->form !== false) {
            $this->beginForm();
        }

        $this->beginModal();

        if (isset($this->body)) {
            if ($this->body instanceof \Closure) {
                print call_user_func($this->body, $this->model);
            } else {
                print $this->body;
            }
        }
    }

    /**
     * Initialization of options
     * @throws InvalidConfigException
     */
    protected function initOptions()
    {
        if (!($this->model instanceof Model)) {
            throw new InvalidConfigException('Model is required');
        }

        if (!($this->model->getPrimaryKey())) {
            throw new InvalidConfigException('Model has empty primary key');
        }

        if ($this->button !== false) {
            $this->button['position'] = isset($this->button['position']) ? $this->button['position'] : static::BUTTON_OUTSIDE;
        }

        if (empty($this->scenario)) {
            $this->scenario = $this->model->scenario;
        } else {
            $this->_oldScenario = $this->model->scenario;
            $this->model->scenario = $this->scenario;
        }

        if ($this->form !== false) {
            $formConfig = [
                'method' => 'POST',
                'action' => $this->scenario,
                'options' => [
                    'class' => 'inline',
                    'data' => [
                        'modal-form' => true
                    ]
                ],
            ];
            if ($this->submit === static::SUBMIT_PJAX) {
                $formConfig['options'] = ArrayHelper::merge($formConfig['options'], [
                    'data' => ['pjax' => 1, 'pjax-push' => 0]
                ]);
            } elseif ($this->submit === static::SUBMIT_AJAX) {
                $formConfig['options'] = ArrayHelper::merge($formConfig['options'], [
                    'data' => ['ajax-submit' => 1]
                ]);

                $this->registerAjaxSubmit();
            }

            $this->form = ArrayHelper::merge($formConfig, $this->form);
        }

        if (is_array($footer = $this->modal['footer'])) {
            $tag = ArrayHelper::remove($footer, 'tag', 'input');
            $label = ArrayHelper::remove($footer, 'label', 'OK');
            $footer = ArrayHelper::merge([
                'data-modal-submit' => true,
            ], $footer);

            if ($tag === 'input') {
                $footer['type']  = 'submit';
            }

            $this->modal['footer'] = Html::tag($tag, $label, $footer);
            $this->registerFooterButtonScript();
        }

        $this->modal = ArrayHelper::merge([
            'id' => $this->getModalId(),
            'toggleButton' => ($this->button['position'] === static::BUTTON_IN_MODAL) ? $this->button : false,
        ], $this->modal);
    }

    /**
     * Runs widget
     */
    public function run()
    {
        $this->endModal();

        if ($this->form !== false) {
            $this->endForm();
        }

        if ($this->_oldScenario !== null) {
            $this->model->scenario = $this->_oldScenario;
        }
    }

    /**
     * Renders toggle button
     */
    public function renderButton()
    {
        if (($button = $this->button) !== false) {
            $tag = ArrayHelper::remove($button, 'tag', 'a');
            $label = ArrayHelper::remove($button, 'label',
                Inflector::camel2words(Inflector::id2camel($this->scenario)));
            if ($tag === 'button' && !isset($button['type'])) {
                $toggleButton['type'] = 'button';
            }

            if ($button['disabled']) {
                $button = ArrayHelper::merge([
                    'onClick' => new JsExpression("return false"),
                ], $button);
            } else {
                $button = ArrayHelper::merge([
                    'data-toggle' => 'modal',
                    'data-target' => "#{$this->getModalId()}",
                ], $button);
            }

            if ($tag === 'a' && empty($button['href'])) {
                $button['href'] = '#';
            }

            echo Html::tag($tag, $label, $button);
        }
    }

    /**
     * Constructs model ID, using [[$model]] primary key, or ID of the widget and scenario
     * @return string format: ```modal_{id}_{scenario}```
     */
    public function getModalId()
    {
        $id = $this->model->getPrimaryKey() ?: $this->id;

        return "modal_{$id}_{$this->scenario}";
    }

    /**
     * Begins form
     */
    public function beginForm()
    {
        $this->form = ActiveForm::begin($this->form);
        echo Html::activeHiddenInput($this->model, 'id');
    }

    /**
     * Ends form
     */
    public function endForm()
    {
        $this->form->end();
    }

    /**
     * Begins modal widget
     */
    public function beginModal()
    {
        Modal::begin($this->modal);
    }

    /**
     * Ends modal widget
     */
    public function endModal()
    {
        Modal::end();
    }

    /**
     * Registers JavaScript for ajax submit
     * @return void
     */
    public function registerAjaxSubmit() {
        $view = Yii::$app->view;

        $options = ArrayHelper::merge([
            'type' => new JsExpression("form.attr('method')"),
            'url' => new JsExpression("form.attr('action')"),
            'data' => new JsExpression("form.serialize()"),
        ], $this->ajaxOptions);
        $options = Json::encode($options);

        $view->registerJs(<<<JS
            $('form[data-ajax-submit]').on('submit', function(event) {
                var form = $(this);
                if (event.eventPhase === 2) {
                    $.ajax($options);
                    $('.modal-backdrop').remove();
                }
                event.preventDefault();
            });
JS
        );
    }

    public function registerFooterButtonScript() {
        $view = Yii::$app->view;
        $view->registerJs("
            $('form[data-modal-form]').on('beforeSubmit', function (e) {
                var submit = $(this).find('[data-modal-submit]');
                if (!submit) return true;
                submit.button('loading');
            });
        ");
    }
}
