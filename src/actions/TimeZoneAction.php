<?php

declare(strict_types=1);

namespace hipanel\actions;

use Yii;
use yii\web\Cookie;

class TimeZoneAction extends Action
{
    public function run(): void
    {
        $timezone = $this->controller->request->post('timezone', false);
        if ($timezone) {
            $this->controller->response->cookies->add(new Cookie([
                'name' => 'timezone',
                'value' => $timezone,
                'expire' => time() + 86400,
            ]));
        }
        Yii::$app->end();
    }
}
