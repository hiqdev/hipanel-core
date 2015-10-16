<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartUpdateAction
 */
class SmartUpdateAction extends SwitchAction
{
    /**
     * @var array|\Closure additional data passed to view
     */
    public $data = [];

    /**
     * @var array additional data passed to model find method
     */
    public $findOptions = [];

    public function init()
    {
        parent::init();
        $this->addItems([
            'POST xeditable' => [
                'class' => 'hipanel\actions\XEditableAction',
            ],
            'GET | POST selection' => [
                'class'  => 'hipanel\actions\RenderAction',
                'data'   => $this->data,
                'params' => function ($action) {
                    $ids = Yii::$app->request->post('selection') ?: Yii::$app->request->post('selection') ?: Yii::$app->request->get('id');
                    $models = $action->controller->findModels($ids, $this->findOptions);
                    return [
                        'models' => $models,
                        'model' => reset($models)
                    ];
                },
            ],
            'POST html' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => function ($action) {
                        return count($action->collection->count()) > 1
                            ? $action->controller->getSearchUrl(['ids' => $action->collection->ids])
                            : $action->controller->getActionUrl('view', ['id' => $action->model->id])
                        ;
                    }
                ],
                'error'   => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => function ($action) {
                        return count($action->collection->count()) > 1
                            ? $action->controller->getSearchUrl(['ids' => $action->collection->ids])
                            : $action->controller->getActionUrl('view', ['id' => $action->model->id]);
                    }
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
