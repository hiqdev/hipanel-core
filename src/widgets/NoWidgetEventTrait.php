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
        return true;
    }

    public function afterRun($result)
    {
        return $result;
    }
}
