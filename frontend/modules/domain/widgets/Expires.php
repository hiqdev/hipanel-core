<?php

namespace frontend\modules\domain\widgets;

class Expires extends \frontend\components\widgets\Label
{
    public $model = [];

    public function run () {
        $expires = $this->model->expires;
        if (strtotime("+30 days", time()) < strtotime($expires)) $class = 'info';
        elseif (strtotime("+0 days", time()) < strtotime($expires)) $class = 'warning';
        else $class = 'danger';

        $this->zclass   = $class;
        $this->label    = \Yii::$app->formatter->asDate($expires);
        parent::run();
    }

}
