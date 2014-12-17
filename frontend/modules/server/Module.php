<?php

namespace app\modules\server;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\server\controllers';
    public $defaultRoute = 'server/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
