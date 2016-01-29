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

    /** @inheritdoc */
    protected function getDefaultRules()
    {
        return array_merge(parent::getDefaultRules(), [
            'POST ajax' => [
                'save' => true,
                'flash' => false,
                'success' => [
                    'class' => RenderJsonAction::class,
                    'return' => function ($action) {
                        return $action->collection->models;
                    }
                ]
            ],
            'POST pjax' => [
                'save'    => true,
                'success' => [
                    'class' => ViewAction::class,
                    'view'  => $this->pjaxView,
                ],
            ],
            'POST' => [
                'save'    => true,
                'success' => [
                    'class' => RedirectAction::class,
                ],
            ],
            'GET' => [
                'class' => RedirectAction::class,
            ],
        ]);
    }
}
