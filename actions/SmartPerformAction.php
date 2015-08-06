<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartPerformAction
 */
class SmartPerformAction extends SwitchAction
{
    public function init()
    {
        parent::init();
        $this->setItems([
            'POST' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                ],
            ],
            'GET' => [
                'class' => 'hipanel\actions\RedirectAction',
            ],
        ]);
    }
}
