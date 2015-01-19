<?php

namespace app\modules\client;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\client\controllers';
    public $defaultRoute = 'client/client';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
