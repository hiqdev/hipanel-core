<?php
namespace frontend\components\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\web\YiiAsset;
use Yii;

/**
 * Class Box
 *
 * @package vova07\themes\admin\widgets
 * Theme Box widget.
 */
class Box extends Widget
{
    /**
     * @var string|null Widget title
     */
    public $title;
    /**
     * @var array Widget buttons array
     * Possible index:
     * `url` - Link URL
     * `label` - Link label
     * `icon` - Link icon class
     * `options` - Link options array
     */
    public $buttons = [];
    /**
     * @var string|null Widget buttons template
     * Example: '{create} {delete}'
     */
    public $buttonsTemplate;
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array Body box widget options
     */
    public $bodyOptions = [];
    /**
     * @var array Body box widget options
     */
    public $footerOptions = [];
    /**
     * @var boolean Whether to render or not the box body
     */
    public $renderBody = true;
    /**
     * @var string|null Grid ID
     * This variable is used by `batch-delete` button.
     */
    public $grid;
    /**
     * @var string Param name that is sent by ajax on batch deleting
     */
    public $batchParam = 'ids';
    /**
     * @var string Param name of delete action
     */
    public $deleteParam = 'id';

    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();
        $this->initOptions();
        $this->initButtons();
        // Begin box
        echo Html::beginTag('div', $this->options) . "\n";
        if ($this->title !== null || !empty($this->buttons)) {
            // Begin box header
            echo Html::beginTag('div', ['class' => 'box-header']);
            // Box title
            if ($this->title !== null) {
                echo Html::tag('h3', $this->title, ['class' => 'box-title']);
            }
            // Render buttons
            $this->renderButtons();
            // End box header
            echo Html::endTag('div');
        }
        if ($this->renderBody === true) {
            // Beign box body
            echo Html::beginTag('div', $this->bodyOptions) . "\n";
        }
    }

    /**
     * @inheritdoc
     */
    public function run () {
        $this->registerClientScripts();
        if ($this->renderBody === true) {
            echo "\n" . Html::endTag('div'); // End box body
        }
        echo "\n" . Html::endTag('div'); // End box
    }

    /**
     * Begin box body container.
     */
    public function beginBody () {
        echo Html::beginTag('div', $this->bodyOptions) . "\n";
    }

    /**
     * End box body container.
     */
    public function endBody () {
        echo "\n" . Html::endTag('div');
    }

    /**
     * Begin box footer container.
     */
    public function beginFooter () {
        echo Html::beginTag('div', $this->footerOptions) . "\n";
    }

    /**
     * End box footer container.
     */
    public function endFooter () {
        echo "\n" . Html::endTag('div');
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions () {
        $this->options['class']       = isset($this->options['class']) ? 'box ' . $this->options['class'] : 'box';
        $this->bodyOptions['class']   = isset($this->bodyOptions['class']) ? 'box-body ' . $this->bodyOptions['class'] : 'box-body';
        $this->footerOptions['class'] = isset($this->footerOptions['class']) ? 'box-footer ' . $this->footerOptions['class'] : 'box-footer';
    }

    /**
     * Initializes the widget buttons.
     */
    protected function initButtons () {
        if (!isset($this->buttons['create'])) {
            $this->buttons['create'] = [
                'url'     => ['create'],
                'icon'    => 'fa-plus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('app', 'Create')
                ]
            ];
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = [
                'url'     => ['delete', $this->deleteParam => Yii::$app->request->get($this->deleteParam)],
                'icon'    => 'fa-trash-o',
                'options' => [
                    'class'        => 'btn-default',
                    'title'        => Yii::t('app', 'Delete'),
                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'data-method'  => 'delete'
                ]
            ];
        }
        if (!isset($this->buttons['batch-delete'])) {
            $this->buttons['batch-delete'] = [
                'url'     => ['batch-delete'],
                'icon'    => 'fa-trash-o',
                'options' => [
                    'id'    => 'batch-delete',
                    'class' => 'btn-default',
                    'title' => Yii::t('app', 'Delete selected')
                ]
            ];
        }
        if (!isset($this->buttons['cancel'])) {
            $this->buttons['cancel'] = [
                'url'     => ['index'],
                'icon'    => 'fa-reply',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('app', 'Cancel')
                ]
            ];
        }
    }

    /**
     * Render widget tools button.
     */
    protected function renderButtons () {
        // Box tools
        if ($this->buttonsTemplate !== null && !empty($this->buttons)) {
            // Begin box tools
            echo Html::beginTag('div', ['class' => 'box-tools pull-right']);
            echo preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
                $name = $matches[1];
                if (isset($this->buttons[$name])) {
                    $label                                    = isset($this->buttons[$name]['label']) ? $this->buttons[$name]['label'] : '';
                    $url                                      = isset($this->buttons[$name]['url']) ? $this->buttons[$name]['url'] : '#';
                    $icon                                     = isset($this->buttons[$name]['icon']) ? Html::tag('i', '', ['class' => 'fa ' . $this->buttons[$name]['icon']]) : '';
                    $label                                    = $icon . ' ' . $label;
                    $this->buttons[$name]['options']['class'] = isset($this->buttons[$name]['options']['class']) ? 'btn btn-sm ' . $this->buttons[$name]['options']['class'] : 'btn btn-sm';

                    return Html::a($label, $url, $this->buttons[$name]['options']);
                } else {
                    return '';
                }
            }, $this->buttonsTemplate);
            // End box tools
            echo Html::endTag('div');
        }
    }

    /**
     * Register widgets assets bundles.
     */
    protected function registerClientScripts () {
        if (strpos($this->buttonsTemplate, '{delete}') !== false && isset($this->buttons['delete'])) {
            YiiAsset::register($this->getView());
        }
        if (strpos($this->buttonsTemplate, '{batch-delete}') !== false && $this->grid !== null && isset($this->buttons['batch-delete'])) {
            $view = $this->getView();
            YiiAsset::register($view);
            $view->registerJs("jQuery(document).on('click', '#batch-delete', function (evt) {" . "evt.preventDefault();" . "var keys = jQuery('#" . $this->grid . "').yiiGridView('getSelectedRows');" . "if (keys == '') {" . "alert('" . Yii::t('app', 'You need to select at least one item.') . "');" . "} else {" . "if (confirm('" . Yii::t('app', 'Are you sure you want to delete selected items?') . "')) {" . "jQuery.ajax({" . "type: 'POST'," . "url: jQuery(this).attr('href')," . "data: { " . $this->batchParam . ": keys}" . "});" . "}" . "}" . "});");
        }
    }
}