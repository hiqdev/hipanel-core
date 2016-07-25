<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

class ReminderButton extends Widget
{
    public $object_id;

    public $toggleButton = [];

    public function run()
    {
        $modalId = 'reminder-modal-' . $this->object_id;
        $this->getView()->registerCss("button[data-target='#{$modalId}'] {margin-top: -3px;}");
        return AjaxModal::widget([
            'bulkPage' => false,
            'id' => $modalId,
            'modalFormId' => 'reminder-form-' . $this->object_id,
            'scenario' => 'create',
            'actionUrl' => ['@reminder/create-modal', 'object_id' => $this->object_id],
            'handleSubmit' => Url::toRoute('@reminder/create'),
            'size' => Modal::SIZE_DEFAULT,
            'header' => Html::tag('h4', Yii::t('hipanel/reminder', 'Create new reminder'), ['class' => 'modal-title']),
            'toggleButton' => $this->getToggleButton(),
        ]);
    }

    /**
     * @return mixed
     */
    public function getToggleButton()
    {
        return (!empty($this->toggleButton)) ?
            $this->toggleButton :
            [
                'label' => '<i class="fa fa-bell-o"></i>&nbsp;&nbsp;' . Yii::t('hipanel/reminder', 'Create reminder'),
                'class' => 'btn margin-bottom btn-info pull-right'
            ];
    }

    /**
     * @param mixed $toggleButton
     */
    public function setToggleButton($toggleButton)
    {
        $this->toggleButton = $toggleButton;
    }
}
