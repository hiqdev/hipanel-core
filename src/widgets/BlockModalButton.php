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
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\FontIcon;
use Yii;
use yii\base\Event;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class BlockModalButton
 * Used for special render of object blocking modal window with activating button.
 */
class BlockModalButton extends Widget
{
    const ACTION_ENABLE = 'enable';
    const ACTION_DISABLE = 'disable';

    const SCENARIO_ENABLE = 'enable-block';
    const SCENARIO_DISABLE = 'disable-block';

    const EVENT_BEFORE_BODY = 'beforeBody';
    const EVENT_AFTER_BODY = 'afterBody';

    /**
     * @var integer ID of action
     *
     * @see ACTION_DISABLE
     * @SEE ACTION_ENABLE
     */
    public $action;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $scenario;

    /**
     * @var array|Modal stores the overriding options for [[ModalButton]].
     * After Modal creating, stores the object.
     */
    public $modal = [];

    /**
     * @var string the validation URL, will be passed to [[ModalButton::form]]
     * Default: `validate-form?scenario={$this->scenario}`
     */
    public $validationUrl;

    /**
     * @var array block reasons. Should be a key-value array, will be passed to [[Html::dropDown]]
     * Default: `Yii::$app->controller->getBlockReasons()`
     */
    public $blockReasons;

    /**
     * @var array Options for trigger button. Will be passed to [[ModalButton::button]]
     */
    public $button = [];

    /**
     * @var array Options to render the header of modal.
     * Keys with special behaviour:
     *  - tag - html tag, used to wrap the header label (default: h4)
     *  - label - the value of tag
     *
     * All other options will be passed as third argument options to [[Html::tag]]
     */
    public $header = [];

    /**
     * @var array Options to render the warning message inside of the modal.
     * Keys with special behaviour:
     *  - tag - html tag, used to wrap the label (default: h4)
     *  - label - the value of tag
     *
     * All other options will be passed as third argument options to [[Html::tag]]
     */
    public $warning = [];

    /**
     * @var array
     */
    public $footer = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * Configures options of widget.
     */
    public function initOptions()
    {
        $model = $this->model;
        if ($this->action === null) {
            $this->action = $model->state === $model::STATE_BLOCKED ? self::ACTION_DISABLE : self::ACTION_ENABLE;
        }

        if ($this->scenario === null) {
            $this->scenario = $this->action === static::ACTION_ENABLE ? static::SCENARIO_ENABLE : static::SCENARIO_DISABLE;
        }

        if ($this->validationUrl === null) {
            $this->validationUrl = Url::toRoute(['validate-form', 'scenario' => $this->scenario]);
        }

        if ($this->blockReasons === null) {
            $this->blockReasons = Yii::$app->controller->getBlockReasons();
        }

        if (is_array($this->button)) {
            $this->button = ArrayHelper::merge([
                static::ACTION_ENABLE => [
                    'label' => FontIcon::i('fa-lock fa-2x fa-fw') . Yii::t('hipanel', 'Enable block'),
                    'position' => ModalButton::BUTTON_OUTSIDE,
                ],
                static::ACTION_DISABLE => [
                    'label' => FontIcon::i('fa-unlock fa-2x fa-fw') . Yii::t('hipanel', 'Disable block'),
                    'position' => ModalButton::BUTTON_OUTSIDE,
                ],
            ], $this->button);
            $this->button = $this->button[$this->action];
        }

        if (is_array($this->header)) {
            $this->header = ArrayHelper::merge([
                static::ACTION_ENABLE => [
                    'label' => Yii::t('hipanel', 'Are you sure you want to block this object'),
                    'class' => 'label-danger',
                ],
                static::ACTION_DISABLE => [
                    'label' => Yii::t('hipanel', 'Are you sure you want to unblock this object'),
                    'class' => 'label-info',
                ],
            ], $this->header);

            $this->header = $this->header[$this->action];
        }

        if (is_array($this->warning)) {
            $this->warning = ArrayHelper::merge([
                static::ACTION_ENABLE => false,
                static::ACTION_DISABLE => [
                    'label' => Yii::t('hipanel', 'Check whether all of the violations were eliminated'),
                ],
            ], $this->warning);

            $this->warning = $this->warning[$this->action];
        }

        if (is_array($this->footer)) {
            $this->footer = ArrayHelper::merge([
                static::ACTION_ENABLE => [
                    'label' => Yii::t('hipanel', 'Enable block'),
                    'data-loading-text' => Yii::t('hipanel', 'loading...'),
                    'class' => 'btn btn-danger',
                ],
                static::ACTION_DISABLE => [
                    'label' => Yii::t('hipanel', 'Disable block'),
                    'data-loading-text' => Yii::t('hipanel', 'loading...'),
                    'class' => 'btn btn-info',
                ],
            ], $this->footer);

            $this->footer = $this->footer[$this->action];
        }
    }

    /**
     * Begins modal.
     * @throws \yii\base\InvalidConfigException
     */
    protected function modalBegin()
    {
        $config = ArrayHelper::merge([
            'class' => ModalButton::class,
            'model' => $this->model,
            'scenario' => $this->scenario,
            'button' => $this->button,
            'form' => [
                'enableAjaxValidation' => true,
                'validationUrl' => $this->validationUrl,
            ],
            'modal' => [
                'header' => Html::tag(ArrayHelper::remove($this->header, 'tag', 'h4'),
                    ArrayHelper::remove($this->header, 'label')),
                'headerOptions' => $this->header,
                'footer' => $this->footer,
            ],
        ], $this->modal);

        $this->modal = call_user_func([ArrayHelper::remove($config, 'class'), 'begin'], $config);
    }

    /**
     * Ends modal.
     */
    protected function modalEnd()
    {
        $this->modal->end();
    }

    /**
     * Renders the body of the Modal.
     * Triggers [[EVENT_BEFORE_BODY]] and [[EVENT_AFTER_BODY]]
     * Set `$event->handled = true` to prevent default body render.
     */
    protected function renderBody()
    {
        $event = new Event();
        $this->trigger(static::EVENT_BEFORE_BODY, $event);
        if ($event->handled) {
            return;
        }

        if ($this->warning) {
            echo Html::tag('div',
                Html::tag(
                    ArrayHelper::remove($this->warning, 'tag', 'h4'),
                    ArrayHelper::remove($this->warning, 'label'),
                    $this->warning
                ),
                ['class' => 'callout callout-warning']
            );
        }

        if ($this->blockReasons) {
            echo $this->modal->form->field($this->model, 'type')->dropDownList($this->blockReasons);
        }
        echo $this->modal->form->field($this->model, 'comment');

        $this->trigger(static::EVENT_AFTER_BODY);
    }

    /**
     * Renders widget.
     */
    public function run()
    {
        $this->modalBegin();
        $this->renderBody();
        $this->modalEnd();
    }
}
