<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

/**
 * Class SmartPerformAction.
 */
class SmartPerformAction extends SwitchAction
{
    /** @var string The name of view, that will be rendered for pjax request */
    public $pjaxView = 'view';

    /** {@inheritdoc} */
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
                    },
                ],
            ],
            'POST pjax' => [
                'save'    => true,
                'success' => [
                    'class'  => ProxyAction::class,
                    'action' => $this->pjaxView,
                    'params' => function ($action, $model) {
                        return ['id' => $model->id];
                    },
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
