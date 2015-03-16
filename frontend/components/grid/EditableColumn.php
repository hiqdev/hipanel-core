<?php

namespace frontend\components\grid;
use Yii;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Class DataColumn
 * Our DataColumn widget.
 */
class EditableColumn extends \kartik\grid\EditableColumn
{
    /**
     * @inheritdoc
     */
    public $popover = null;

    /**
     * @inheritdoc
     */
    public $popoverOptions = [
        'placement'     => 'bottom',
        'selector'      => 'a',
    ];

    /**
     * action for form
     */
    public $action = null;

    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();
        $this->prepareEditableOptions();
        $this->registerClientScript();
    }

    public function renderHeaderCellContent () {
        $this->headerOptions = ArrayHelper::merge($this->headerOptions,[
            'data-toggle'  => 'popover',
            'data-trigger' => 'hover',
            'data-content' => $this->popover,
        ]);
        return parent::renderHeaderCellContent();
    }

    public function registerClientScript () {
        $view = Yii::$app->getView();
        $ops = Json::encode($this->popoverOptions);
        $view->registerJs("$('#{$this->grid->id} thead th[data-toggle=\"popover\"]').popover($ops);", \yii\web\View::POS_READY);
    }

    public function prepareEditableOptions () {
        $old = $this->editableOptions;
        $this->editableOptions = function ($model, $key, $index) use ($old) {
            $pkey = reset($model->primaryKey());
            $params = Html::hiddenInput($model->formName()."[$index][$pkey]", $key);
            $ops = $old instanceof \Closure ? call_user_func($old,$model,$key,$index) : $old;
            if (!is_array($ops)) {
                $ops = [];
            };
            if ($this->action) {
                $ops['formOptions']['action'] = $this->action;
            };
            $old = $ops['beforeInput'];
            $ops['beforeInput'] = function ($form, $widget) use ($old, $params) {
                return $params.($old instanceof \Closure ? call_user_func($old, $form, $widget) : $old);
            };
            return $ops;
        };
    }

}
