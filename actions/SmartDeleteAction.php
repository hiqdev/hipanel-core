<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartDeleteAction
 */
class SmartDeleteAction extends SwitchAction
{
    public function init()
    {
        parent::init();
        $this->setItems([
            'POST' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => ['index'],
                ],
                'error'   => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => ['index'],
                ],
            ],
            'GET' => [
                'class' => 'hipanel\actions\RedirectAction',
                'url'   => ['index'],
            ],
        ]);
    }
}
