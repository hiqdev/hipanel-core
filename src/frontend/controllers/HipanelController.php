<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\controllers;

use hipanel\actions\ProxyAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use Yii;

/**
 * HiPanel controller.
 * Just redirects to dashboard.
 */
class HipanelController extends \hipanel\base\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => RedirectAction::class,
                'url'   => ['/dashboard/dashboard'],
            ],
/// later just for testing
            'switch'   => [
                'class'      => RenderAction::class,
                'addFlash'   => true,
                'success'    => Yii::t('app', 'DB truncate task has been created successfully'),
                'error'      => Yii::t('app', 'Error while truncating DB'),
                'POST html'  => [
                    'class'  => ProxyAction::class,
                    'action' => 'index',
                ],
                'GET'        => [
                    'class' => RenderAction::class,
                    'view'  => 'index',
                ],
                'POST pjax'  => [
                    'class'   => RenderAction::class,
                    'view'    => 'index',
                ],
                'default'    => [
                    'class' => RedirectAction::class,
                    'url'   => ['index'],
                ],
            ],
            'proxy'    => [
                'class'  => ProxyAction::class,
                'action' => 'index',
            ],
            'render'   => [
                'class' => RenderAction::class,
                'view'  => 'index',
            ],
            'redirect' => [
                'class' => RedirectAction::class,
                'url'   => ['index'],
            ],
        ];
    }

}
