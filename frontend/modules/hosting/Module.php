<?php

namespace app\modules\hosting;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\hosting\controllers';
    public $defaultRoute = 'hosting/accountw';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
