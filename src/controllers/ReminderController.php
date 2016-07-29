<?php

namespace hipanel\controllers;

use DateTime;
use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAjaxAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\models\Reminder;
use hipanel\widgets\ReminderTop;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReminderController extends \hipanel\base\CrudController
{
    public function init()
    {
        $this->viewPath = '@hipanel/views/reminder';
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['count'],
            ],
        ];
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
            'view' => [
                'class' => ViewAction::class,
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
                'on beforeSave' => function ($event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    if (Yii::$app->request->isAjax) {
                        $reminder = Yii::$app->request->post('Reminder');
                        $action->collection->set(Reminder::find()->where(['id' => $reminder['id']])->one());
                        foreach ($action->collection->models as $model) {
                            $model->next_time = (new DateTime($model->next_time))->modify($reminder['next_time'])->format('Y-m-d H:i:s');
                        }
                    }
                },
                'POST ajax' => [ 'save' => true,
                    'success' => [
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            return [
                                'success' => true,
                                'widget' => ReminderTop::widget()
                            ];
                        },
                    ],
                ],
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'POST html | POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
            ],
            'ajax-reminders-list' => [
                'class' => RenderAjaxAction::class,
                'view' => '_ajaxReminderList',
                'params' => function ($action) {
                    $reminders = Reminder::find()->toSite()->all();
                    $remindInOptions = Reminder::reminderNextTimeOptions();

                    return compact(['reminders', 'remindInOptions']);
                }
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

    public function actionGetCount()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $count = Reminder::find()->toSite()->count();

            return compact('count');
        }
    }
}
