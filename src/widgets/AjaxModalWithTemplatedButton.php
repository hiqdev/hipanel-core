<?php

namespace hipanel\widgets;

use yii\helpers\ArrayHelper;
use ReflectionClass;
use yii\base\Widget;
use yii\web\View;

/**
 * Class AjaxModalWithTemplatedButton render AjaxModal markup to the end of body and render toggle button in any
 * place of html with additional toggle button template.
 */
class AjaxModalWithTemplatedButton extends Widget
{
    /**
     * @var array
     */
    public $ajaxModalOptions;

    /**
     * @var string the template used to render a toggle button.
     */
    public $toggleButtonTemplate = "{toggleButton}";

    /**
     * @var array
     */
    private $toggleButtonOptions;

    public function init()
    {
        $this->toggleButtonOptions = ArrayHelper::remove($this->ajaxModalOptions, 'toggleButton');
        $this->ajaxModalOptions['toggleButton'] = false;
        $this->view->on(View::EVENT_END_BODY, function ($event) {
            echo AjaxModal::widget($this->ajaxModalOptions);
        });
    }

    public function run()
    {
        $id = $this->ajaxModalOptions['id'];
        $toggleButton = function () use ($id) {
            $this->options['id'] = $id;
            $this->initOptions();

            return $this->renderToggleButton();
        };
        $obj = (new ReflectionClass(AjaxModal::class))->newInstanceWithoutConstructor();
        $obj->toggleButton = $this->toggleButtonOptions;

        $button = $toggleButton->call($obj);
        if ($button) {
            $button = strtr($this->toggleButtonTemplate, [
                '{toggleButton}' => $button,
            ]);
        }

        return $button;
    }
}
