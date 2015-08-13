<?php

namespace hipanel\actions;

use Closure;
use Yii;

/**
 * Class SmartCreateAction
 */
class SmartCreateAction extends SwitchAction
{
    public $data;

    public function prepareData()
    {
        if ($this->data instanceof Closure) {
            return call_user_func($this->data, $this);
        } else {
            return (array)$this->data;
        }
    }

    public function init()
    {
        parent::init();
        $this->addItems([
            'GET' => [
                'class'  => 'hipanel\actions\RenderAction',
                'view'   => 'create',
                'params' => function ($action) {
                    $model = $action->controller->newModel(['scenario' => $action->scenario]);
                    return array_merge([
                        'model'  => $model,
                        'models' => [$model],
                    ], $this->prepareData());
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
                    'view'   => 'create',
                    'params' => [
                        'models' => function ($action) {
                            return $action->collection->models;
                        },
                    ],
                ],
            ],
        ]);
    }
}
