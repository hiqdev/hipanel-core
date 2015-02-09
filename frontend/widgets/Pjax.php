<?php

/**
 * Created by PhpStorm.
 * User: SilverFire
 * Date: 27.01.2015
 * Time: 13:57
 */
namespace frontend\widgets;

class Pjax extends \yii\widgets\Pjax {
    public function init() {
        parent::init();
        Alert::widget();
    }
}