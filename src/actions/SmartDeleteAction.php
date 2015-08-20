<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartDeleteAction
 */
class SmartDeleteAction extends SmartPerformAction
{
    public function init()
    {
        parent::init();
        $this->addItems([
            'POST html | POST pjax' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => 'index'
                ],
            ],
        ], 'first');
    }
}
