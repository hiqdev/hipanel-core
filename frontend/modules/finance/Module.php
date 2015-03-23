<?php

namespace frontend\modules\finance;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\finance\controllers';
    public $defaultRoute = 'bill/index';

    public function init()
    {
        parent::init();
    }
}
