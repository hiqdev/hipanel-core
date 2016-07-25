<?php

namespace hipanel\widgets;

use hipanel\models\Reminder;
use yii\base\Widget;

class ReminderTop extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $count = Reminder::find()->count();
        $remindInOptions = Reminder::reminderNextTimeOptions();
        $reminders = Reminder::find()->all();
        return $this->render('ReminderTop', [
            'count' => $count,
            'reminders' => $reminders,
            'remindInOptions' => $remindInOptions,
        ]);
    }
}
