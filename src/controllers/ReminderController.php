<?php

namespace hipanel\controllers;

use DateTime;
use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use Symfony\Component\EventDispatcher\Event;
use Yii;
use yii\base\Widget;
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

                    return $data;
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'view' => 'create-modal',
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'on beforeSave' => function(Event $event) {
                    $action = $event->sender;
                    $postData = Yii::$app->request->post('Reminder');
                    foreach ($action->collection->models as $model) {
                        $model->netx_time = (new DateTime())->modify($postData['next_time'])->format('Y-m-d H:i:s');
                    }
                },
//                'POST ajax' => [
//                    'save' => true,
//                    'success' => [
//                        'class' => RenderJsonAction::class,
//                        'return' => function ($action) {
//                            return ['success' => true]; // todo: wise resulting
//                        },
//                    ],
//                ],
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

    public function getPeriodicityOptions()
    {
        return $this->getRefs('type,periodicity', 'hipanel/reminder');
    }

    public function getTypeReminder()
    {
        return $this->getRefs('type,reminder', 'hipanel/reminder');
    }
}
