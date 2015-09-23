<?php

namespace hipanel\actions;

use Closure;
use Yii;

/**
 * Class SmartCreateAction
 */
class SmartCreateAction extends SwitchAction
{
    /**
     * @var string View name, used for render of result on GET request
     */
    public $view;

    public function run()
    {
        $this->view = $this->view ?: $this->getScenario();
        parent::run();
    }

    public function init()
    {
        parent::init();
        $this->addItems([
            'GET' => [
                'class'  => 'hipanel\actions\RenderAction',
                'view'   => $this->view,
                'data'   => $this->data,
                'params' => function ($action) {
                    $model = $action->controller->newModel(['scenario' => $action->scenario]);
                    return [
                        'model'  => $model,
                        'models' => [$model],
                    ];
                },
            ],
            'POST' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => function ($action) {
                        return count($action->collection->models)>1
                            ? $action->controller->getSearchUrl(['ids' => $action->collection->ids])
                            : $action->controller->getActionUrl('view', ['id' => $action->model->id])
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
        ]);
    }
}
