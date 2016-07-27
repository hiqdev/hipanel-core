<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\controllers;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
                'success'    => Yii::t('hipanel', 'Success'),
                'error'      => Yii::t('hipanel', 'Error'),
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
