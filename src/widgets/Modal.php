<?php

namespace hipanel\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class Modal extends \yii\bootstrap\Modal
{
    public $scenario;

    public function init()
    {
        $this->initAdditionalOptions();
        parent::init();
    }

    protected function initAdditionalOptions()
    {
        $scenario = Url::to($this->scenario);
        $modalId = $this->getId();
        $loadingHtml = Json::htmlEncode($this->getLoadHtml());
        $this->clientEvents['show.bs.modal'] = new JsExpression("function() {
            jQuery('#{$modalId} .modal-body').load('{$scenario}');
        }");
        $this->clientEvents['hidden.bs.modal'] = new JsExpression("function() {
            jQuery('#{$modalId} .modal-body').html({$loadingHtml});
        }");
    }

    protected function renderBodyBegin()
    {
        return Html::beginTag('div', ['class' => 'modal-body']) . $this->getLoadHtml();
    }

    protected function getLoadHtml()
    {
        return '
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span class="sr-only">' . Yii::t('app', 'loading') . '...</span>
            </div>
        </div>
        ';
    }
}