<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 23.03.15
 * Time: 14:05
 */
namespace frontend\components\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class Box extends Widget
{
    /**
     * @var string|null Widget title
     */
    public $title = null;

    /**
     * @var array Widget buttons array
     * Possible index:
     * `label` - Link label
     * `icon` - Link icon class
     * `options` - Link options array
     */
    public $boxTools = [];

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
     * @var array Header box widget options
     */
    public $headerOptions = [];

    /**
     * @var boolean Whether to render or not the box body
     */
    public $renderBody = true;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->initOptions();
        $this->initBoxTools();
        // Begin box
        echo Html::beginTag('div', $this->options) . "\n";
        if ($this->title !== null) { // || !empty($this->boxTools)
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
     * Start header section, render title if not exist
     */
    public function beginHeader() {
        echo Html::beginTag('div', $this->headerOptions) . "\n";
        if ($this->title !== null) {
            print Html::tag('h3', $this->title, ['class' => 'box-title']);
        }
    }

    /**
     * End of header section
     */
    public function endHeader() {
        print "\n" . Html::endTag('div');
    }

    /**
     * @inheritdoc
     */
    public function run() {
        if ($this->renderBody === true) {
            print "\n" . Html::endTag('div'); // End box body
        }
        print "\n" . Html::endTag('div'); // End box
    }

    /**
     * Begin box body container.
     */
    public function beginBody() {
        print Html::beginTag('div', $this->bodyOptions) . "\n";
    }

    /**
     * End box body container.
     */
    public function endBody() {
        print "\n" . Html::endTag('div');
    }

    /**
     * Begin box footer container.
     */
    public function beginFooter() {
        print Html::beginTag('div', $this->footerOptions) . "\n";
    }

    /**
     * End box footer container.
     */
    public function endFooter() {
        print "\n" . Html::endTag('div');
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions() {
        $this->options['class'] = isset($this->options['class']) ? 'box ' . $this->options['class'] : 'box';
        $this->bodyOptions['class'] = isset($this->bodyOptions['class']) ? 'box-body ' . $this->bodyOptions['class'] : 'box-body';
        $this->footerOptions['class'] = isset($this->footerOptions['class']) ? 'box-footer ' . $this->footerOptions['class'] : 'box-footer';
    }

    /**
     * Initializes the widget buttons.
     */
    protected function initBoxTools() {
        if (!isset($this->boxTools['create'])) {
            $this->boxTools['create'] = [
                'url' => ['create'],
                'icon' => 'fa-plus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('app', 'Create')
                ]
            ];
        }
        //        if (!isset($this->buttons['delete'])) {
        //            $this->buttons['delete'] = [
        //                'url'     => ['delete', $this->deleteParam => Yii::$app->request->get($this->deleteParam)],
        //                'icon'    => 'fa-trash-o',
        //                'options' => [
        //                    'class'        => 'btn-default',
        //                    'title'        => Yii::t('app', 'Delete'),
        //                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        //                    'data-method'  => 'delete'
        //                ]
        //            ];
        //        }
        //        if (!isset($this->buttons['batch-delete'])) {
        //            $this->buttons['batch-delete'] = [
        //                'url'     => ['batch-delete'],
        //                'icon'    => 'fa-trash-o',
        //                'options' => [
        //                    'id'    => 'batch-delete',
        //                    'class' => 'btn-default',
        //                    'title' => Yii::t('app', 'Delete selected')
        //                ]
        //            ];
        //        }
        //        if (!isset($this->buttons['cancel'])) {
        //            $this->buttons['cancel'] = [
        //                'url'     => ['index'],
        //                'icon'    => 'fa-reply',
        //                'options' => [
        //                    'class' => 'btn-default',
        //                    'title' => Yii::t('app', 'Cancel')
        //                ]
        //            ];
        //        }
    }

    /**
     * Render widget tools button.
     */
    protected function renderButtons() {
        // Box tools
        if ($this->boxTools) {
            // Begin box tools
            print Html::beginTag('div', ['class' => 'box-tools pull-right']);
            print preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
                $name = $matches[1];
                if (isset($this->boxTools[$name])) {
                    $label                                    = isset($this->boxTools[$name]['label']) ? $this->boxTools[$name]['label'] : '';
                    $icon                                     = isset($this->boxTools[$name]['icon']) ? Html::tag('i', '', ['class' => 'fa ' . $this->boxTools[$name]['icon']]) : '';
                    $label                                    = $icon . ' ' . $label;
                    $this->boxTools[$name]['options']['class'] = isset($this->boxTools[$name]['options']['class']) ? 'btn btn-sm ' . $this->boxTools[$name]['options']['class'] : 'btn btn-sm';

                    return Html::a($label, $url, $this->boxTools[$name]['options']);
                } else {
                    return '';
                }
            }, $this->buttonsTemplate);
            // End box tools
            print Html::endTag('div');
        }
    }
}