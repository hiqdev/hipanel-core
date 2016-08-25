<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

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

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();
        $searchFormId = Json::htmlEncode("#{$this->getBulkFormId()}");
        $this->originalContext = Yii::$app->view->context;
        $view = $this->getView();
        $view->registerJs(<<<JS
        // Checkbox
        var checkboxes = $('table input[type="checkbox"]');
        var bulkcontainer = $('.box-bulk-actions fieldset');
        checkboxes.on('ifChecked ifUnchecked', function(event) {
            if (event.type == 'ifChecked' && $('input.icheck').filter(':checked').length > 0) {
                bulkcontainer.prop('disabled', false);
            } else if ($('input.icheck').filter(':checked').length == 0) {
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
JS
);
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
        return $this->contents[$name];
    }

    public function run()
    {
        return $this->render($this->getLayout());
    }

    public function detectLayout()
    {
        $os = Yii::$app->get('orientationStorage');
        $n = $os->get(Yii::$app->controller->getRoute());
        return $n;
    }

    public function setSearchFormData($data = [])
    {
        $this->searchFormData = $data;
    }

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
            foreach (['per_page', 'representation'] as $key) {
                echo Html::hiddenInput($key, Yii::$app->request->get($key));
            }
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
        return IndexLayoutSwitcher::widget();
    }

    public function renderPerPage()
    {
        return ButtonDropdown::widget([
            'label' => Yii::t('hipanel', 'Per page') . ': ' . (Yii::$app->request->get('per_page') ?: 25),
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => [
                    ['label' => '25',  'url' => Url::current(['per_page' => null])],
                    ['label' => '50',  'url' => Url::current(['per_page' => 50])],
                    ['label' => '100', 'url' => Url::current(['per_page' => 100])],
                    ['label' => '200', 'url' => Url::current(['per_page' => 200])],
                    ['label' => '500', 'url' => Url::current(['per_page' => 500])],
                ],
            ],
        ]);
    }

    /**
     * Renders button to choose representation.
     * Returns empty string when nothing to choose (less then 2 representations available).
     * @param array $grid class
     * @param mixed $current selected representation
     * @return string rendered HTML
     */
    public static function renderRepresentations($grid, $current)
    {
        $representations = $grid::getRepresentations();
        if (count($representations)<2) {
            return '';
        }
        if (!isset($representations[$current])) {
            $current = key($representations);
        }
        $items = [];
        foreach ($representations as $key => $data) {
            $items[] = [
                'label' => $data['label'],
                'url'   => Url::current(['representation' => $key]),
            ];
        }

        return ButtonDropdown::widget([
            'label' => Yii::t('synt', 'View') . ': ' . $representations[$current]['label'],
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => $items,
            ],
        ]);
    }

    public function renderSorter(array $options)
    {
        return LinkSorter::widget(array_merge([
            'show'  => true,
            'sort'  => $this->dataProvider->getSort(),
            'buttonClass' => 'btn btn-default dropdown-toggle btn-sm',
        ], $options));
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

    public function renderBulkButton($text, $action, $color = 'default')
    {
        return Html::submitButton($text, [
            'class'         => "btn btn-$color btn-sm",
            'form'          => $this->getBulkFormId(),
            'formmethod'    => 'POST',
            'formaction'    => $action,
        ]);
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
