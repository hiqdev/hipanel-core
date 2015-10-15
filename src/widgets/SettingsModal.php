<?php

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\helpers\Html;
use hipanel\helpers\FontIcon;

/**
 * Class SettingsModal. Render AjaxModal, created specially to render on view page
 * @package hipanel\widgets
 */
class SettingsModal extends AjaxModal {
    /**
     * @var Model
     */
    public $model;

    /**
     * @var string text of toggle link
     */
    public $title;

    /**
     * @var string icon class for toggle link
     * Will be passed through [[FontIcon::i()]] method
     */
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
