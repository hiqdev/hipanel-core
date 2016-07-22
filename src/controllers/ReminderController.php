<?php

namespace hipanel\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use yii\web\NotFoundHttpException;

class ReminderController extends \hipanel\base\CrudController
{
    public function init()
    {
        $this->viewPath = '@hipanel/views/reminder';
    }

    public function actions()
    {
        return [
            'set-orientation' => [
                'class' => OrientationAction::class,
                'allowedRoutes' => [
                    'reminder/index'
                ]
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action, $data) {
                    return [

                    ];
                },
            ],
            'create-modal' => [
                'class' => SmartCreateAction::class,
                'scenario' => 'create',
                'view' => 'create-modal',
                'data' => function ($action, $data) {
                    $object_id = \Yii::$app->request->get('object_id');
                    if (empty($object_id)) {
                        throw new NotFoundHttpException('Object ID is missing');
                    }
                    $data['model']->object_id = $object_id;
                    $data['periodicityOptions'] = $this->getRefs('type,periodicity', 'hipanel/reminder');

                    return $data;
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'POST ajax' => [
                    'save' => true,
                    'success' => '123123123',
                ],
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'POST html | POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
            ]
        ];
    }
}
