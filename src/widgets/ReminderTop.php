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
        $query = Reminder::find()->where(['to_site' => true]);
        $count = $query->count();
        $remindInOptions = Reminder::reminderNextTimeOptions();
        $reminders = $query->all();
        return $this->render('ReminderTop', [
            'count' => $count,
            'reminders' => $reminders,
            'remindInOptions' => $remindInOptions,
        ]);
    }
}
