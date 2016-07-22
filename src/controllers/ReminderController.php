<?php

namespace hipanel\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\PrepareAjaxViewAction;
use hipanel\actions\RenderAjaxAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;

class ReminderController extends \hipanel\base\CrudController
{
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
                'view' => '@hipanel/views/reminder/index',
                'data' => function ($action, $data) {
                    return [

                    ];
                },
            ],
            'open-create-modal' => [
                'class' => PrepareAjaxViewAction::class,
                'view' => '@hipanel/views/reminder/_form',
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'view' => '@hipanel/views/reminder/create',
                'GET ajax' => [
                    'class' => RenderAjaxAction::class,
                    'view' => $this->view,
                    'data' => $this->data,
                    'params' => function ($action) {
                        $model = $action->controller->newModel(['scenario' => $action->scenario]);
                        return [
                            'model' => $model,
                            'models' => [$model],
                        ];
                    },
                ],
                'data' => function ($action, $data) {

                    return [

                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'view' => '@hipanel/views/reminder/update',
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
