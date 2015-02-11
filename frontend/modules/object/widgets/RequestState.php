<?php
namespace frontend\modules\object\widgets;

use frontend\modules\object\assets\RequestStateAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use frontend\components\Re;

class RequestState extends Widget
{

    public $module;

    /**
     * @var \frontend\components\hiresource\ActiveRecord
     */
    public $model;

    public function init () {
        parent::init();
        if ($this->model->request_state_label) {
            $this->model->request_state_label = Re::l($this->model->request_state_label);
        }

        if ($this->model->state_label) {
            $this->model->state_label = Re::l($this->model->state_label);
        }

        if (empty($this->module)) throw new InvalidConfigException('module name is not specified');
    }

    public function run () {
        if ($this->model->request_state) {
            $icon = Html::tag('i', '', [
                    'class' => ($this->model->request_state != 'error') ? 'fa fa-circle-o-notch fa-spin' : 'fa fa-exclamation-triangle text-danger'
                ]);

            $res = Html::tag('span', $icon . ' ' . $this->model->request_state_label, [
                    'class' => 'objectState',
                    'data'  => [
                        'id'         => $this->model->id,
                        'module'     => $this->module,
                        'norm_state' => $this->model->state_label,
                        'with_href'  => 0
                    ]
                ]);
        } else {
            $res = Html::tag('span', $this->model->state_label);
        }

        RequestStateAsset::register(\Yii::$app->getView());
        \Yii::$app->getView()->registerJs("$('html,body').objectsStateWatcher({
            'module': '{$this->module}'
        });", \yii\web\View::POS_READY, 'objectStateWatcher-'.$this->module);
        return $res;
    }
}

