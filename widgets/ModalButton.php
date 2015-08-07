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
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\Modal;
use Yii;

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
     * @var ActiveRecord the model. Is required.
     */
    public $model;

    /**
     * @var string Used to generate form action URL and HTML id of modal.
     * May be set manually, otherwise will be extracted from the model
     */
    public $scenario;

    /**
     * @var array The options for the HTML form.
     * The following special options are supported:
     *
     * - action: string|array, the action, that will be passed as first argument to [[Html::beginForm()]]
     * - method: string, the method, that will be passed as second argument to [[Html::beginForm()]]
     *
     * The rest of the options will be rendered as the HTML attributes of the form.
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

        if (empty($this->model->getPrimaryKey())) {
            throw new InvalidConfigException('Model has empty primary key');
        }

        if ($this->button !== false) {
            $this->button['position'] = isset($this->button['position']) ? $this->button['position'] : static::BUTTON_OUTSIDE;
        }

        if (empty($this->scenario)) {
            $this->scenario = $this->model->scenario;
        }

        if ($this->form !== false) {
            $this->form = ArrayHelper::merge([
                'data'  => ['pjax' => 1, 'pjax-push' => 0],
                'class' => 'inline'
            ], $this->form);
        }

        if (is_array($footer = $this->modal['footer'])) {
            $tag    = ArrayHelper::remove($footer, 'tag', 'button');
            $label  = ArrayHelper::remove($footer, 'label', 'OK');
            $footer = ArrayHelper::merge([
                'onClick' => new \yii\web\JsExpression("
                    $(this).closest('form').trigger('submit');
                    $(this).button('loading');
                ")
            ], $footer);

            $this->modal['footer'] = Html::tag($tag, $label, $footer);
        }

        $this->modal = ArrayHelper::merge([
            'id'           => $this->getModalId(),
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
    }

    /**
     * Renders toggle button
     */
    public function renderButton()
    {
        if (($button = $this->button) !== false) {
            $tag   = ArrayHelper::remove($button, 'tag', 'a');
            $label = ArrayHelper::remove($button, 'label', Inflector::camel2words(Inflector::id2camel($this->scenario)));
            if ($tag === 'button' && !isset($button['type'])) {
                $toggleButton['type'] = 'button';
            }
            $button = ArrayHelper::merge([
                'data-toggle' => 'modal',
                'data-target' => "#{$this->getModalId()}",
            ], $button);

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
        $action = ArrayHelper::remove($this->form, 'action', $this->scenario);
        $method = ArrayHelper::remove($this->form, 'method', "POST");
        echo Html::beginForm($action, $method, $this->form);
        echo Html::activeHiddenInput($this->model, 'id');
    }

    /**
     * Ends form
     */
    public function endForm()
    {
        echo Html::endForm();
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
}
