<?php

namespace hipanel\widgets;

use Yii;
use yii\helpers\Html;
use hipanel\helpers\FontIcon;

class SettingsModal extends AjaxModal {
    public $model;

    public $title;

    public $icon;

    public function init()
    {
        $this->header = Html::tag('h4', $this->title, ['class' => 'modal-title']);
        $this->actionUrl = [$this->scenario, 'id' => $this->model->id];
        $this->toggleButton = [
            'tag'   => 'a',
            'label' => FontIcon::i($this->icon) . $this->title,
            'class' => 'clickable',
        ];
        parent::init();
    }
}
