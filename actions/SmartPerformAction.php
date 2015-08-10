<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartPerformAction
 */
class SmartPerformAction extends SwitchAction
{
    /** @var string The name of view, that will be rendered for pjax request */
    public $pjaxView = 'view';

    public function init()
    {
        parent::init();
        $this->addItems([
            'POST pjax' => [
                'save'    => true,
                'success' => [
                    'class'       => 'hipanel\actions\ViewAction',
                    'view'        => $this->pjaxView,
                ],
            ],
            'POST'      => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                ],
            ],
            'GET'       => [
                'class' => 'hipanel\actions\RedirectAction',
            ],
        ]);
    }
}
