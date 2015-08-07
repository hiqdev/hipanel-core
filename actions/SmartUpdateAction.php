<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartUpdateAction
 */
class SmartUpdateAction extends SwitchAction
{
    public function init()
    {
        parent::init();
        $this->setItems([
            'POST xeditable' => [
                'class' => 'hipanel\actions\XEditableAction',
            ],
            'GET' => [
                'class'  => 'hipanel\actions\RenderAction',
                'params' => [
                    'models' => function ($action) {
                        $ids = Yii::$app->request->get('selection') ?: Yii::$app->request->get('id');
                        return $action->controller->findModels($ids);
                    },
                ],
            ],
            'POST html' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => function ($action) {
                        return $action->collection->count()
                            ? $action->controller->getSearchUrl(['ids' => $action->collection->ids])
                            : $action->controller->getActionUrl('view', ['id' => $action->model->id])
                        ;
                    }
                ],
                'error'   => [
                    'class'  => 'hipanel\actions\RenderAction',
                    'params' => [
                        'models' => function ($action) {
                            return $action->collection->models;
                        },
                    ],
                ],
            ],
            'POST pjax' => [
                'save'    => true,
                'success' => [
                    'class'  => 'hipanel\actions\ProxyAction',
                    'action' => 'view',
                    'params' => function ($action, $model) {
                        return ['id' => $model->id];
                    },
                ]
            ],
        ]);
    }
}
