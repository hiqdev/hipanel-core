<?php

namespace hipanel\actions;

use Closure;
use hipanel\base\FilterStorage;
use hipanel\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class IndexAction
 *
 */
class IndexAction extends SearchAction
{
    /**
     * @var string view to render.
     */
    public $view = 'index';

    /**
     * @var array The map of filters for the [[hipanel\base\FilterStorage|FilterStorage]]
     */
    public $filterStorageMap = [];

    public function init()
    {
        parent::init();

        $this->dataProviderOptions = ArrayHelper::merge($this->dataProviderOptions, [
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per_page') ?: 25
            ]
        ]);
    }

    protected function getDefaultRules()
    {
        return array_merge([
            'html | pjax' => [
                'save' => false,
                'flash' => false,
                'success' => [
                    'class'  => RenderAction::class,
                    'view'   => $this->view,
                    'data'   => $this->data,
                    'params' => function () {
                        return [
                            'model'        => $this->getSearchModel(),
                            'dataProvider' => $this->getDataProvider(),
                        ];
                    }
                ]
            ]
        ], parent::getDefaultRules());
    }

    /**
     * @inheritdoc
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $request = Yii::$app->request;

            $formName = $this->getSearchModel()->formName();
            $requestFilters = Yii::$app->request->get($formName) ?: Yii::$app->request->get() ?: Yii::$app->request->post();

            // Don't save filters for ajax requests, because
            // the request is probably triggered with select2 or smt similar
            if ($request->getIsPjax() || !$request->getIsAjax()) {
                $filterStorage = new FilterStorage(['map' => $this->filterStorageMap]);

                if ($request->getIsPost() && $request->post('clear-filters')) {
                    $filterStorage->clearFilters();
                }

                $filters = $filterStorage->get();
                $filterStorage->set($requestFilters);
                $search = ArrayHelper::merge($this->findOptions, $filters, $requestFilters);
            } else {
                $search = ArrayHelper::merge($this->findOptions, $requestFilters);
            }

            $this->returnOptions[$this->controller->modelClassName()] = ArrayHelper::merge(
                ArrayHelper::remove($search, 'return', []),
                ArrayHelper::remove($search, 'rename', [])
            );

            $this->dataProvider = $this->getSearchModel()->search([$formName => $search], $this->dataProviderOptions);
        }

        return $this->dataProvider;
    }
}
