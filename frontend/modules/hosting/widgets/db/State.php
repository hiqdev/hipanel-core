<?php

namespace frontend\modules\hosting\widgets\db;

class State extends \frontend\components\widgets\Label
{
    public $model = [];

    public function run () {
        $state = $this->model->state;
        if ($state=='ok') $class = 'info';
        elseif ($state=='blocked') $class = 'danger';
        else $class = 'warning';

        $this->zclass   = $class;
        $this->label    = \frontend\components\Re::l($this->model->state_label);
        parent::run();
    }
}
