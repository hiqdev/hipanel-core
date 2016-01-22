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
        $this->addItems([
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
        ]);

        parent::init();

        $this->dataProviderOptions = ArrayHelper::merge($this->dataProviderOptions, [
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per_page') ?: 25
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $formName = $this->getSearchModel()->formName();
            $requestFilters = Yii::$app->request->get($formName) ?: Yii::$app->request->get() ?: Yii::$app->request->post();

            $filterStorage = new FilterStorage(['map' => $this->filterStorageMap]);
            $filters = $filterStorage->get();

            $search = ArrayHelper::merge($this->findOptions, $filters, $requestFilters);

            $filterStorage->set($requestFilters);

            $this->returnOptions[$this->controller->modelClassName()] = ArrayHelper::merge(
                ArrayHelper::remove($search, 'return', []),
                ArrayHelper::remove($search, 'rename', [])
            );

            $this->dataProvider = $this->getSearchModel()->search([$formName => $search], $this->dataProviderOptions);
        }

        return $this->dataProvider;
    }
}
