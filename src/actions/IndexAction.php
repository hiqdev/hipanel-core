<?php

declare(strict_types=1);
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hipanel\base\FilterStorage;
use hipanel\grid\RepresentationCollectionFinder;
use hipanel\grid\RepresentationCollectionFinderInterface;
use hipanel\widgets\SynchronousCountEnabler;
use hiqdev\hiart\ActiveDataProvider;
use hiqdev\higrid\representations\RepresentationCollection;
use hiqdev\higrid\representations\RepresentationCollectionInterface;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\Controller;

/**
 * Class IndexAction.
 */
class IndexAction extends SearchAction
{
    public const VARIANT_PAGER_RESPONSE = 'pager';
    public const VARIANT_SUMMARY_RESPONSE = 'summary';
    /**
     * GET AJAX answer options for `VariantAction`, for example:
     * ```
     *      [
     *          'headerValue1' => fn(VariantAction $action): string => 'response1',
     *          'headerValue2' => fn(VariantAction $action): string => 'response2',
     *      ],
     * ```
     * @var array
     */
    public array $responseVariants = [];

    /**
     * @var string view to render
     */
    protected $_view;

    /**
     * @var RepresentationCollectionFinder
     */
    private $representationCollectionFinder;

    public function getRepresentationCollectionFinder(): RepresentationCollectionFinderInterface
    {
        return $this->representationCollectionFinder;
    }

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

    public function __construct(
        string $id,
        Controller $controller,
        RepresentationCollectionFinder $representationCollectionFinder,
        array $config = []
    )
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
            'html | pjax | POST ids' => [
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
            'GET ajax' => [ // todo: make to able to receive the POST requests
                'class' => VariantsAction::class,
                'variants' => array_merge([
                    self::VARIANT_PAGER_RESPONSE => fn(VariantsAction $action): string => (new SynchronousCountEnabler(
                        $action->parent->getDataProvider(),
                        fn(GridView $grid): string => $grid->renderPager(),
                    ))->preventModelsLoading()->__invoke(),
                    self::VARIANT_SUMMARY_RESPONSE => fn(VariantsAction $action): string => (new SynchronousCountEnabler(
                        $action->parent->getDataProvider(),
                        fn(GridView $grid): string => $grid->renderSummary(),
                    ))(),

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
    protected function ensureRepresentationCollection(): RepresentationCollection|RepresentationCollectionInterface
    {
        return $this->representationCollectionFinder->findOrFallback();
    }

    public function getDataProvider(): ActiveDataProvider
    {
        if ($this->dataProvider === null) {
            $requestFilters = $this->getRequestFilters();
            $this->applyFiltersFromStorage($requestFilters);
            $search = $this->detectSearchQuery($requestFilters);
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

    public function detectSearchQuery(?array $requestFilters): array
    {
        $formName = $this->getSearchModel()->formName();
        $search = ArrayHelper::merge($this->findOptions, $requestFilters);
        $this->returnOptions[$this->controller::modelClassName()] = ArrayHelper::merge(
            ArrayHelper::remove($search, 'return', []),
            ArrayHelper::remove($search, 'rename', [])
        );

        if ($formName !== '') {
            $search = [$formName => $search];
        }

        return $search;
    }

    public function getRequestFilters(): ?array
    {
        $formName = $this->getSearchModel()->formName();
        if ($this->controller->request->get($formName)) {
            return $this->controller->request->get($formName);
        }

        return [...$this->controller->request->get(null, []), ...$this->controller->request->post(null, [])];
    }

    public function applyFiltersFromStorage(?array &$requestFilters = []): void
    {
        // Don't save filters for ajax requests, because
        // the request is probably triggered with select2 or smt similar
        if (($this->controller->request->getIsPjax() || !$this->controller->request->getIsAjax())) {
            $filterStorage = new FilterStorage(['map' => $this->filterStorageMap]);
            if ($this->controller->request->isPost && $this->controller->request->post('clear-filters')) {
                $filterStorage->clearFilters();
            }
            $filterStorage->set($requestFilters);
            // Apply filters from storage only when request does not contain any data
            if (empty($requestFilters)) {
                $requestFilters = $filterStorage->get();
            }
        }
    }
}
