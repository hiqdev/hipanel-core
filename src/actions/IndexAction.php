<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use Closure;
use hipanel\base\FilterStorage;
use hipanel\grid\RepresentationCollectionFinder;
use hipanel\widgets\CountEnabler;
use hiqdev\higrid\representations\RepresentationCollection;
use hiqdev\higrid\representations\RepresentationCollectionInterface;
use Yii;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\Controller;

/**
 * Class IndexAction.
 */
class IndexAction extends SearchAction
{
    /**
     * @var string view to render
     */
    protected $_view;

    public array $responseVariants = [];

    /**
     * @var RepresentationCollectionFinder
     */
    private $representationCollectionFinder;

    public function setView($value)
    {
        $this->_view = $value;
    }

    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = lcfirst(Inflector::id2camel($this->id));
        }

        return $this->_view;
    }

    public function __construct(string $id, Controller $controller, RepresentationCollectionFinder $representationCollectionFinder, array $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->representationCollectionFinder = $representationCollectionFinder;
    }

    /**
     * @var array The map of filters for the [[hipanel\base\FilterStorage|FilterStorage]]
     */
    public $filterStorageMap = [];

    protected function getDefaultRules()
    {
        return ArrayHelper::merge([
            'html | pjax' => [
                'save' => false,
                'flash' => false,
                'success' => [
                    'class' => RenderAction::class,
                    'view' => $this->getView(),
                    'data' => $this->data,
                    'params' => function () {
                        return [
                            'model' => $this->getSearchModel(),
                            'dataProvider' => $this->getDataProvider(),
                            'representationCollection' => $this->ensureRepresentationCollection(),
                            'uiModel' => $this->getUiModel(),
                        ];
                    },
                ],
            ],
            'GET ajax' => [
                'class' => VariantsAction::class,
                'variants' => array_merge([
                    'pager' => fn(VariantsAction $action): string => CountEnabler::widget([
                        'dataProvider' => $action->parent->getDataProvider(),
                        'content' => fn(GridView $grid): string => $grid->renderPager(),
                    ]),
                    'summary' => fn(VariantsAction $action): string => CountEnabler::widget([
                        'dataProvider' => $action->parent->getDataProvider(),
                        'content' => fn(GridView $grid): string => $grid->renderSummary(),
                    ]),
                ], $this->responseVariants),
            ],
        ], parent::getDefaultRules());
    }

    public function getUiModel()
    {
        return $this->controller->indexPageUiOptionsModel;
    }

    /**
     * Method tries to guess representation collection class name and create object
     * Creates empty collection when no specific representation exists.
     *
     * @return RepresentationCollection|RepresentationCollectionInterface
     */
    protected function ensureRepresentationCollection()
    {
        return $this->representationCollectionFinder->findOrFallback();
    }

    /**
     * {@inheritdoc}
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $request = Yii::$app->request;

            $formName = $this->getSearchModel()->formName();
            $requestFilters = $request->get($formName) ?: $request->get() ?: $request->post();

            // Don't save filters for ajax requests, because
            // the request is probably triggered with select2 or smt similar
            if ($request->getIsPjax() || !$request->getIsAjax()) {
                $filterStorage = new FilterStorage(['map' => $this->filterStorageMap]);

                if ($request->getIsPost() && $request->post('clear-filters')) {
                    $filterStorage->clearFilters();
                }

                $filterStorage->set($requestFilters);

                // Apply filters from storage only when request does not contain any data
                if (empty($requestFilters)) {
                    $requestFilters = $filterStorage->get();
                }
            }

            $search = ArrayHelper::merge($this->findOptions, $requestFilters);

            $this->returnOptions[$this->controller->modelClassName()] = ArrayHelper::merge(
                ArrayHelper::remove($search, 'return', []),
                ArrayHelper::remove($search, 'rename', [])
            );

            if ($formName !== '') {
                $search = [$formName => $search];
            }
            $this->dataProvider = $this->getSearchModel()->search($search, $this->dataProviderOptions);

            // Set sort
            if ($this->getUiModel()->sort) {
                $this->dataProvider->setSort(['defaultOrder' => [$this->getUiModel()->sortAttribute => $this->getUiModel()->sortDirection]]);
            }

            // Set pageSize
            if ($this->getUiModel()->per_page) {
                $this->dataProvider->setPagination(['pageSize' => $this->getUiModel()->per_page]);
            }
        }

        return $this->dataProvider;
    }
}
