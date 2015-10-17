<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartUpdateAction
 * @property string view
 */
class SmartUpdateAction extends SwitchAction
{
    /**
     * @var array|\Closure additional data passed to view
     */
    public $data = [];

    /**
     * @var string The view that represents current update action
     */
    public $_view;

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
                'view'   => $this->view,
                'params' => function ($action) {
                    $ids = Yii::$app->request->post('selection') ?: Yii::$app->request->post('selection') ?: Yii::$app->request->get('id');
                    $models = $action->controller->findModels($ids, $this->findOptions);
                    foreach ($models as $model) {
                        $model->scenario = $this->scenario;
                    }
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
                            : $action->controller->getActionUrl('view', ['id' => $action->collection->first->id])
                        ;
                    }
                ],
                'error'   => [
                    'class'  => 'hipanel\actions\RenderAction',
                    'view'   => $this->view,
                    'data'   => $this->data,
                    'params' => function ($action) {
                        return [
                            'model'  => $action->collection->first,
                            'models' => $action->collection->models,
                        ];
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

    /**
     * @return string
     */
    public function getView()
    {
        return $this->_view ?: $this->scenario;
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->_view = $view;
    }
}
