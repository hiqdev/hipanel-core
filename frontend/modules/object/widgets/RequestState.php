<?php
namespace app\modules\object\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use frontend\components\Re;

class RequestState extends Widget {

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
    }

    public function run () {
        if ($this->model->request_state) {
            $icon = Html::tag('i',
            '',
            [
                'class' => ($this->model->request_state != 'error') ? 'fa fa-circle-o-notch fa-spin' : 'fa fa-exclamation-triangle text-danger'
            ]);

            $res = Html::tag('span',
                $icon . ' ' . $this->model->request_state_label,
                [
                    'class'           => 'objectState',
                    'data-id'         => $this->model->id,
                    'data-norm_state' => $this->model->state_label,
                    'data-with_href'  => 0 /// TODO: CRUD implementation: 0 for users, 1 for admins
                ]);
        } else {
            $res = Html::tag('span', $this->model->state_label);
        }

        return $res;
    }
}

