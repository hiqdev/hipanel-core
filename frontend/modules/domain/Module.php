<?php

namespace app\modules\domain;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\domain\controllers';
    public $defaultRoute = 'domain/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
