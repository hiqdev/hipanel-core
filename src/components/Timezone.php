<?php

declare(strict_types=1);

namespace hipanel\components;

use hidev\base\Component;
use hipanel\helpers\Url;
use Yii;
use yii\web\Application;
use yii\web\View;

class Timezone extends Component
{
    public function init()
    {
        if (!Yii::$app->request->cookies->has('timezone')) {
            Yii::$app->on(Application::EVENT_BEFORE_REQUEST, function ($event) {
                /** @var View $view */
                $view = $event->sender->view;
                $actionRoute = Url::to('/site/timezone');
                $js = /** @lang JavaScript */ "
                  ;(() => { 
                    $.post('$actionRoute', { timezone: Intl.DateTimeFormat().resolvedOptions().timeZone });
                  })();
                ";
                $view->registerJs($js);
            });
        }
    }
}
