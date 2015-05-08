<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\controllers;

use Yii;

/**
 * HiPanel controller
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

    public function actions()
    {
//        return $this->render('index', ['model' => $model]);
        return [
            'switch'   => [
                'class'      => 'hipanel\actions\SwitchAction',
                'addFlash'   => true,
                'success'    => Yii::t('app', 'DB truncate task has been created successfully'),
                'error'      => Yii::t('app', 'Error while truncating DB'),
                'POST html'  => [
                    'class'  => 'hipanel\actions\ProxyAction',
                    'action' => 'index',
                ],
                'GET'        => [
                    'class' => 'hipanel\actions\RenderAction',
                    'view'  => 'index',
                ],
                'POST pjax'  => [
                    'class'   => 'hipanel\actions\RenderAction',
                    'view'    => 'index',
                ],
                'default'    => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => ['index'],
                ],
            ],
            'proxy'    => [
                'class'  => 'hipanel\actions\ProxyAction',
                'action' => 'index',
            ],
            'render'   => [
                'class' => 'hipanel\actions\RenderAction',
                'view'  => 'index',
            ],
            'redirect' => [
                'class' => 'hipanel\actions\RedirectAction',
                'url'   => ['index'],
            ],
        ];
    }

}
