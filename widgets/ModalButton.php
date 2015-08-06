<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;

class ModalButton extends Widget
{
    const BUTTON_INSIDE   = 1;
    const BUTTON_OUTSIDE  = 2;
    const BUTTON_IN_MODAL = 3;

    public $model;
    public $scenario;

    public $form;
    public $button;
    public $body;
    public $options;

    public function init() {
        $this->initOptions();

        print Html::beginTag('div', $this->options) . "\n";

        if ($this->button['position'] === static::BUTTON_OUTSIDE && isset($this->button['label'])) {
            $this->renderOutsideButton();
        }
        if (isset($this->body)) {
            if ($this->body instanceof \Closure) {
                print call_user_func($this->body, $this->model);
            } else {
                print $this->body;
            }
        }
    }

    protected function initOptions()
    {
        if ($this->button !== false) {
            $this->button['position'] = isset($this->button['position']) ? $this->button['position'] : static::BUTTON_OUTSIDE;
        }

        if (empty($this->scenario)) {
            $this->scenario = $this->model->scenario;
        }

        if ($this->form !== false) {
            $this->form = ArrayHelper::merge([
                'action' => $this->scenario,
                'method' => "POST",
            ], $this->form);
        }
    }

    public function run() {
        $this->endBody();
    }

    public function renderOutsideButton() {
        if (($button = $this->button) !== false) {
            $tag = ArrayHelper::remove($button, 'tag', 'button');
            $label = ArrayHelper::remove($button, 'label', 'Show');
            if ($tag === 'button' && !isset($button['type'])) {
                $toggleButton['type'] = 'button';
            }

            return Html::tag($tag, $label, $button);
        } else {
            return null;
        }
    }

    public function getModalId() {
        $id = $this->model->id ?: $this->id;

        return "modal_{$id}_{$this->scenario}";
    }
}
