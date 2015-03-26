<?php

namespace app\modules\client;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\client\controllers';
    public $defaultRoute = 'client/index';

    public function init()
    {
        parent::init();
    }
}
