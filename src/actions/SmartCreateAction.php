<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

/**
 * Class SmartCreateAction.
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
        return parent::run();
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge(parent::getDefaultRules(), [
            'GET ajax' => [
                'class' => RenderAjaxAction::class,
                'view' => $this->view,
                'data' => $this->data,
                'params' => function ($action) {
                    $model = $action->controller->newModel(['scenario' => $action->scenario]);
                    return [
                        'model'  => $model,
                        'models' => [$model],
                    ];
                },
            ],
            'GET' => [
                'class'  => RenderAction::class,
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
            'POST ajax' => [
                'save'    => true,
                'flash' => false,
                'success' => [
                    'class' => RenderJsonAction::class,
                    'return'   => function ($action) {
                        return ['success' => true]; // todo: wise resulting
                    },
                ],
                'error'   => [
                    'class'  => RenderAjaxAction::class,
                    'view'   => $this->view,
                    'data'   => $this->data,
                    'params' => function ($action) {
                        \Yii::$app->response->statusCode = 422;
                        $error = \Yii::$app->session->removeFlash('error');
                        \Yii::$app->response->statusText = reset($error)['text'];

                        return [
                            'model'  => $action->collection->first,
                            'models' => $action->collection->models,
                        ];
                    },
                ],
            ],
            'POST' => [
                'save'    => true,
                'success' => [
                    'class' => RedirectAction::class,
                    'url'   => function ($action) {
                        return $action->collection->count() > 1
                            ? $action->controller->getSearchUrl(['id_in' => $action->collection->ids])
                            : $action->controller->getActionUrl('view', ['id' => $action->model->id]);
                    },
                ],
                'error'   => [
                    'class'  => RenderAction::class,
                    'view'   => $this->view,
                    'data'   => $this->data,
                    'params' => function ($action) {
                        return [
                            'model'  => $action->collection->first,
                            'models' => $action->collection->models,
                        ];
                    },
                ],
            ],
        ]);
    }
}
