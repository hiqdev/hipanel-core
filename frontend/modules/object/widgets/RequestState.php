<?php
namespace frontend\modules\object\widgets;

use frontend\modules\object\assets\RequestStateAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use frontend\components\Re;
use yii\helpers\Json;

class RequestState extends Widget
{

    public $module;

    /**
     * @var \frontend\components\hiresource\ActiveRecord
     */
    public $model;

    /**
     * @var array additional options to be passed to the JS plugin call.
     */
    public $clientOptions = array();

    /**
     * @var string default selector of wrapper with state labels. Will be passed to JS plugin call.
     */
    public $elementSelector = "#content-pjax";

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

        $options   = Json::encode(array_merge(['module' => $this->module], $this->clientOptions));
        $plugin_id = 'objectStateWatcher-' . $this->module;
        \Yii::$app->getView()->registerJs("$('{$this->elementSelector}').objectsStateWatcher($options);", \yii\web\View::POS_READY, $plugin_id);

        return $res;
    }
}

