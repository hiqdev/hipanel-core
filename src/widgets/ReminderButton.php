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

    public $toggleButton;

    public function run()
    {
        return AjaxModal::widget([
            'bulkPage' => false,
            'id' => 'reminder-modal-' . $this->object_id,
            'scenario' => 'create',
            'actionUrl' => ['@reminder/create-modal', 'object_id' => $this->object_id],
            'handleSubmit' => Url::toRoute('@reminder/create'),
            'size' => Modal::SIZE_LARGE,
            'header' => Html::tag('h4', Yii::t('hipanel', 'Create new reminder'), ['class' => 'modal-title']),
            'toggleButton' => [
                'tag' => 'a',
                'label' => '<i class="fa fa-bell"></i>' . Yii::t('hipanel', 'Create reminder'),
                'class' => 'clickable'
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function getToggleButton()
    {
        return $this->toggleButton !== null ?
            $this->toggleButton :
            [
                'tag' => 'a',
                'label' => '<i class="fa fa-bell"></i>' . Yii::t('hipanel', 'Create reminder'),
                'class' => 'clickable'
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
