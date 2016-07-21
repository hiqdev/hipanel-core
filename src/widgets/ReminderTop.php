<?php

namespace hipanel\widgets;

use yii\base\Widget;

class ReminderTop extends Widget
{
    public function init()
    {
       parent::init(); 
    }

    public function run()
    {
       return $this->render('ReminderTop', [

       ]);
    }
}
