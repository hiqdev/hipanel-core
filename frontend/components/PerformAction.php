<?php

namespace frontend\components;

class PerformAction extends \yii\base\Action
{
    public function run () {
        return $this->controller->perform(['scenario' => $this->id]);
    }
}
