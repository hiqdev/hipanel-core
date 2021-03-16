<?php

namespace hipanel\widgets;

use yii\base\WidgetEvent;

trait NoWidgetEventTrait
{
    public function init()
    {
    }

    public function beforeRun()
    {
        return (new WidgetEvent())->isValid;
    }

    public function afterRun($result)
    {
        $event = new WidgetEvent();
        $event->result = $result;

        return $event->result;
    }
}
