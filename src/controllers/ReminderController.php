<?php

namespace hipanel\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
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
                }
            ],
            'create' => [
                'class' => SmartCreateAction::class,
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
            ]
        ];
    }

    /**
     * @return array
     */
    protected function prepareRefs()
    {
        return [
            'topic_data' => $this->getRefs('topic,ticket', 'hipanel/ticket'),
            'state_data' => $this->getClassRefs('state', 'hipanel/ticket'),
            'priority_data' => $this->getPriorities(),
        ];
    }
}
