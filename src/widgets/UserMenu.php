<?php

namespace hipanel\widgets;

use yii\base\Widget;

class UserMenu extends Widget
{
    public function run()
    {
        return $this->render('UserMenu');
    }
}
