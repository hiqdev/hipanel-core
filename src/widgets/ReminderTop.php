<?php

namespace hipanel\widgets;

use hipanel\assets\ReminderTopAsset;
use hipanel\models\Reminder;
use yii\base\Widget;

class ReminderTop extends Widget
{
    public function init()
    {
        parent::init();
        $this->registerClientScript();
    }

    public function run()
    {
        $count = Reminder::find()->toSite()->count();
        $remindInOptions = Reminder::reminderNextTimeOptions();
        return $this->render('ReminderTop', [
            'count' => $count,
            'remindInOptions' => $remindInOptions,
        ]);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        ReminderTopAsset::register($view);
        $view->registerJs("
           alert('asdfasdfasdf');         
        ");
    }
}
