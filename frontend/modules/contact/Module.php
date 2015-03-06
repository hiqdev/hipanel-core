<?php

namespace app\modules\contact;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\contact\controllers';
    public $defaultRoute = 'contact/contact';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
