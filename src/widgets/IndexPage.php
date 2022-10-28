<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use Closure;
use hipanel\assets\StickySidebarAsset;
use hipanel\grid\RepresentationCollectionFinder;
use hipanel\helpers\ArrayHelper;
use hipanel\models\IndexPageUiOptions;
use hiqdev\higrid\representations\RepresentationCollectionInterface;
use hiqdev\yii2\export\widgets\ExportProgress;
use hiqdev\yii2\export\widgets\IndexPageExportLinks;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

class IndexPage extends Widget
{
    /**
     * @var string
     */
    protected $_layout;

    /**
     * @var Model the search model
     */
    public $model;

    /**
     * @var IndexPageUiOptions
     */
    private $uiModel;

    /**
     * @var object original view context.
     * It is used to render sub-views with the same context, as IndexPage
     */
    public $originalContext;

    /**
     * @var DataProviderInterface
     */
    public $dataProvider;

    /**
     * @var array Hash of document blocks, that can be rendered later in the widget's views
     * Blocks can be set explicitly on widget initialisation, or by calling [[beginContent]] and
     * [[endContent]]
     *
     * @see beginContent
     * @see endContent
     */
    public $contents = [];

    /**
     * @var string the name of current content block, that is under the render
     * @see beginContent
     * @see endContent
     */
    protected $_current = null;

    /**
     * @var array
     */
    public $searchFormData = [];

    /**
     * @var array
     */
    public $searchFormOptions = [];

    /**
     * @var string the name of view file that contains search fields for the index page. Defaults to `_search`
     * @see renderSearchForm()
     */
    public $searchView = '_search';

    public ?Closure $insteadPerPageRender = null;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();
        $searchFormId = Json::htmlEncode("#{$this->getBulkFormId()}");
        $this->originalContext = Yii::$app->view->context;
        $view = $this->getView();
        // Fix a very narrow select2 input in the search tables
        $view->registerCss('#content-pjax .select2-dropdown--below { min-width: 170px!important; }');
        $view->registerJs(<<<"JS"
        // Checkbox
        var bulkcontainer = $('.box-bulk-actions fieldset');
        $($searchFormId).on('change', 'input[type="checkbox"]', function(event) {
            var checkboxes = $('input.grid-checkbox');
            if (checkboxes.filter(':checked').length > 0) {
                bulkcontainer.prop('disabled', false);
            } else if (checkboxes.filter(':checked').length === 0) {
                bulkcontainer.prop('disabled', true);
            }
        });
        // On/Off Actions TODO: reduce scope
        $(document).on('click', '.box-bulk-actions a', function (event) {
            var link = $(this);
            var action = link.data('action');
            var form = $($searchFormId);
            if (action) {
                form.attr({'action': action, method: 'POST'}).submit();
            }
        });
        // Do not open select2 when it is clearing
        var comboSelector = 'div[role=grid] :input[data-combo-field], .advanced-search :input[data-combo-field]';
        $(document).on('select2:unselecting', comboSelector, function(e) {
            $(e.target).data('unselecting', true);
        }).on('select2:open', comboSelector, function(e) { // note the open event is important
            var el = $(e.target);
            if (el.data('unselecting')) {
                el.removeData('unselecting'); // you need to unset this before close
                el.select2('close');
            }
        });
JS
        );
    }

    public function getUiModel()
    {
        if ($this->uiModel === null) {
            $this->uiModel = $this->originalContext->indexPageUiOptionsModel ?? $this->originalContext->uiModel;
        }

        return $this->uiModel;
    }

    /**
     * Begins output buffer capture to save data in [[contents]] with the $name key.
     * Must not be called nested. See [[endContent]] for capture terminating.
     * @param string $name
     */
    public function beginContent($name)
    {
        if ($this->_current) {
            throw new InvalidParamException('Output buffer capture is already running for ' . $this->_current);
        }
        $this->_current = $name;
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Terminates output buffer capture started by [[beginContent()]].
     * @see beginContent
     */
    public function endContent()
    {
        if (!$this->_current) {
            throw new InvalidParamException('Outout buffer capture is not running. Call beginContent() first');
        }
        $this->contents[$this->_current] = ob_get_contents();
        ob_end_clean();
        $this->_current = null;
    }

    /**
     * Returns content saved in [[content]] by $name.
     * @param string $name
     * @return string
     */
    public function renderContent($name)
    {
        return $this->contents[$name] ?? '';
    }

    public function run()
    {
        $layout = $this->getLayout();
        if ($layout === 'horizontal') {
            $this->horizontalClientScriptInit();
        }

        return $this->render($layout);
    }

    private function horizontalClientScriptInit()
    {
        $view = $this->getView();
        StickySidebarAsset::register($view);
        $view->registerCss(<<<'CSS'
            .advanced-search[min-width~="150px"] form > div {
                width: 100%;
                position: inherit;
            }
            .horizontal-view .content-sidebar {
                will-change: min-height;
            }
            .horizontal-view .content-sidebar .content-sidebar__inner {
                transform: translate(0, 0); /* For browsers don't support translate3d. */
                transform: translate3d(0, 0, 0);
                will-change: position, transform;
            }
            .horizontal-view .content-sidebar__inner > .btn,
            .horizontal-view .content-sidebar__inner .dropdown > .btn {
                display: block;
                margin-bottom: 10px;
            }
CSS
        );
        $view->registerJs(<<<"JS"
            var isDesktop = $(window).innerWidth() > 991;
            if (isDesktop) {
                var stickySidebar = new StickySidebar('.horizontal-view .content-sidebar', {
                    topSpacing: 10,
                    containerSelector: '.horizontal-content',
                    innerWrapperSelector: '.content-sidebar__inner'
                });
                $(document).on('pjax:end', stickySidebar.updateSticky);
            }

            $(document).on('pjax:end', function() {
                $('.advanced-search form > div').css({'width': '100%'});
                $(window).trigger('scroll'); // Fix left search block position
            });
JS
           , View::POS_LOAD);
    }

    public function detectLayout()
    {
        return $this->getUiModel()->orientation;
    }

    /**
     * @param array $data
     * @void
     */
    public function setSearchFormData($data = [])
    {
        $this->searchFormData = $data;
    }

    /**
     * @param array $options
     * @void
     */
    public function setSearchFormOptions($options = [])
    {
        $this->searchFormOptions = $options;
    }

    public function renderSearchForm($advancedSearchOptions = [])
    {
        $advancedSearchOptions = array_merge($advancedSearchOptions, $this->searchFormOptions);
        ob_start();
        ob_implicit_flush(false);
        try {
            $search = $this->beginSearchForm($advancedSearchOptions);
            echo Yii::$app->view->render($this->searchView, array_merge(compact('search'), $this->searchFormData), $this->originalContext);
            $search->end();
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    public function beginSearchForm($options = [])
    {
        return AdvancedSearch::begin(array_merge(['model' => $this->model], $options));
    }

    public function renderSearchButton()
    {
        return AdvancedSearch::renderButton() . "\n";
    }

    public function renderLayoutSwitcher()
    {
        return IndexLayoutSwitcher::widget(['uiModel' => $this->getUiModel()]);
    }

    public function renderPerPage()
    {
        if ($this->insteadPerPageRender instanceof Closure) {
            return call_user_func($this->insteadPerPageRender, $this);
        }
        $items = [];
        foreach ([25, 50, 100, 200, 500] as $pageSize) {
            $items[] = ['label' => $pageSize, 'url' => Url::current(['per_page' => $pageSize])];
        }

        return ButtonDropdown::widget([
            'label' => Yii::t('hipanel', 'Per page') . ': ' . $this->getUiModel()->per_page,
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => $items,
            ],
        ]);
    }

    /**
     * Renders button to choose representation.
     * Returns empty string when nothing to choose (less then 2 representations available).
     *
     * @param RepresentationCollectionInterface $collection
     * @return string rendered HTML
     */
    public function renderRepresentations($collection)
    {
        $current = $this->getUiModel()->representation;

        $representations = $collection->getAll();
        if (count($representations) < 2) {
            return '';
        }

        $items = [];
        foreach ($representations as $name => $representation) {
            $items[] = [
                'label' => $representation->getLabel(),
                'url' => Url::current(['representation' => $name]),
            ];
        }

        return ButtonDropdown::widget([
            'label' => Yii::t('hipanel:synt', 'View') . ': ' . $collection->getByName($current)->getLabel(),
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => $items,
            ],
        ]);
    }

    public function renderSorter(array $options)
    {
        return LinkSorter::widget(array_merge([
            'show' => true,
            'uiModel' => $this->getUiModel(),
            'dataProvider' => $this->dataProvider,
            'sort' => $this->dataProvider->getSort(),
            'buttonClass' => 'btn btn-default dropdown-toggle btn-sm',
        ], $options));
    }

    public function canShowExport(): bool
    {
        $isGridExportActionExists = (bool) Yii::$app->controller->createAction('export');
        /** @var RepresentationCollectionFinder $repColFinder */
        $repColFinder = Yii::createObject(RepresentationCollectionFinder::class);
        $collection = $repColFinder->findOrFallback();
        $isRepresentationExists = count($collection?->getAll()) > 0;

        return $isGridExportActionExists && $isRepresentationExists;
    }

    public function renderExport()
    {
        if ($this->canShowExport()) {
            return IndexPageExportLinks::widget();
        }
    }

    public function renderExportProgress(): string
    {
        return $this->canShowExport() ? ExportProgress::widget() : '';
    }

    public function getViewPath()
    {
        return parent::getViewPath() . DIRECTORY_SEPARATOR . (new \ReflectionClass($this))->getShortName();
    }

    public function getBulkFormId()
    {
        return 'bulk-' . Inflector::camel2id($this->model->formName());
    }

    public function beginBulkForm($action = '')
    {
        echo Html::beginForm($action, 'POST', ['id' => $this->getBulkFormId()]);
    }

    public function endBulkForm()
    {
        echo Html::endForm();
    }

    /**
     * @param string|array $action
     * @param string $text
     * @param array $options
     * @return string
     */
    public function renderBulkButton($action, $text, array $options = []): string
    {
        $color = ArrayHelper::remove($options, 'color', 'default');
        $confirm = ArrayHelper::remove($options, 'confirm', false);
        if ($confirm) {
            $options['onclick'] = new JsExpression("return confirm('{$confirm}');");
        }
        $defaultOptions = [
            'class' => "btn btn-$color btn-sm",
            'form' => $this->getBulkFormId(),
            'formmethod' => 'POST',
            'formaction' => Url::toRoute($action),
        ];

        return Html::submitButton($text, array_merge($defaultOptions, $options));
    }

    /**
     * @param string $permission
     * @param string $button
     * @return string|null
     */
    public function withPermission(string $permission, string $button): ?string
    {
        if (Yii::$app->user->can($permission)) {
            return $button;
        }
        return null;
    }

    /**
     * @param string|array $action
     * @param string|null $text
     * @param array $options
     * @return string
     */
    public function renderBulkDeleteButton($action, $text = null, array $options = []): string
    {
        $text = $text ?? Yii::t('hipanel', 'Delete');
        $options['color'] = $options['color'] ?? 'danger';
        $options['confirm'] = $options['confirm'] ?? Yii::t('hipanel', 'Are you sure you want to delete these items?');

        return $this->renderBulkButton($action, $text, $options);
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        if ($this->_layout === null) {
            $this->_layout = $this->detectLayout();
        }

        return $this->_layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }
}
