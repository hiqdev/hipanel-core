<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class Block extends Widget {
    private $defaultOptions = [
        'scenario'      => [
            'enable'        => 'enable-block',
            'disable'       => 'disable-block',
        ],
        'headerOptions' => [
            'enable'        => [ 'class' => 'label-danger', ],
            'disable'       => [ 'class' => 'label-info', ],
        ],
        'footer'        => [
            'enable'        => [
                'label'             => 'Enable block',
                'data-loading-text' => 'Enabling block...',
                'class'             => 'btn btn-danger',
            ],
            'disable'       => [
                'label'             => 'Disable block',
                'data-loading-text' => 'Disabling block...',
                'class'             => 'btn btn-info',
           ],
        ],
        'headerTag'     => [
            'enable'        => 'h4',
            'disable'       => 'h4',
        ],
    ];
    public $model;
    public $scenario;
    public $action = 'disable';
    public $button;
    public $validationUrl;
    public $header;
    public $headerOptions;
    public $blockReasons;
    public $footer;
    public $warning;

    public function init () {
        parent::init();
    }

    public function run () {
        foreach ($this->defaultOptions as $option => $value) {
            ${$option} = isset($this->{$option}) ? $this->{$option} : $value[$this->action];
        }
        $model          = $this->model;
        $button         = isset($this->button) ? $this->button : (
                $this->action == 'enable'
                    ? '<i class="fa ion-locked"></i>' . Yii::t('app', 'Enable block')
                    : '<i class="fa ion-unlocked"></i>' . Yii::t('app', 'Disable block')
        );
        $validationUrl  = isset($this->validationUrl) ? $this->validationUrl : Url::toRoute(['validate-form', 'scenario' => $scenario]);
        $blockReasons   = isset($this->blockReasons) ? $this->blockReasons : Yii::$app->controller->getBlockReasons();
        $header         = isset($this->header) ? $this->header : (
                $this->action == 'enable'
                    ? Yii::t('app', 'Confirm enabling block for item')
                    : Yii::t('app', 'Confirm disabling block for item')
        );
        $warning        = isset($this->warning) ? $this->warning : (
                $this->action == 'enable'
                    ? Yii::t('app', 'This will immediately terminate all active sessions and reject new connections!')
                    : Yii::t('app', 'Please check if all violations were eliminated')
        );
        $footer['value'] = $footer['value'] ? : Yii::t('app', $footer['label']);
        return $this->render(
            'Block',
            compact(['model', 'button', 'scenario', 'validationUrl', 'header', 'headerTag', 'headerOptions', 'footer','blockReasons', 'warning'])
        );
    }
}
