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

use yii\base\Widget;
use yii\helpers\Html;

class Box extends Widget
{
    /**
     * @var string|null Widget title
     */
    public $title = null;

    public $collapsed = false;

    public $collapsable = false;

    /**
     * Small helper for title.
     * @var null
     */
    public $small = null;

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
     * @var boolean Whether to render the box body
     */
    public $renderBody = true;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->collapsed) {
            $this->collapsable = true;
        }
        $this->initOptions();
        echo Html::beginTag('div', $this->options) . "\n";
        // Begin box
        if ($this->title !== null) {
            $this->beginHeader();
            $this->endHeader();
        }
        if ($this->renderBody) {
            $this->beginBody();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->getView()->registerCss('
        .box-title-helper {
            padding: 0;
            margin: 0;
            line-height: 13px;
            color: #9eacb4;
            font-size: 13px;
            font-weight: 400;
        }
        ');
        if ($this->renderBody) {
            $this->endBody();
        }
        echo "\n" . Html::endTag('div'); // End box
    }

    /**
     * @param $title
     * @param null $small
     * @return string
     */
    public function renderTitle($title, $small = null)
    {
        $resultTitle = Html::tag('h3', $title, ['class' => 'box-title']);
        $resultSmall = ($small === null) ? '' : Html::tag('span', $small, ['class' => 'box-title-helper']);
        return sprintf('%s %s', $resultTitle, $resultSmall);
    }

    /**
     * Start header section, render title if not exist.
     */
    public function beginHeader()
    {
        echo Html::beginTag('div', $this->headerOptions) . "\n";
        if ($this->title !== null) {
            echo Html::tag('h3', $this->title, ['class' => 'box-title']);
        }
        if ($this->collapsable) {
            echo '<div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-' . ($this->collapsed ? 'plus' : 'minus') . '"></i>
                </button>
            </div>';
        }
    }

    /**
     * End of header section.
     */
    public static function endHeader()
    {
        echo "\n" . Html::endTag('div');
    }

    /**
     * Begin box body container.
     */
    public function beginBody()
    {
        echo Html::beginTag('div', $this->bodyOptions) . "\n";
    }

    /**
     * End box body container.
     */
    public function endBody()
    {
        echo "\n" . Html::endTag('div');
    }

    /**
     * Begin box footer container.
     */
    public function beginFooter()
    {
        echo Html::beginTag('div', $this->footerOptions) . "\n";
    }

    /**
     * End box footer container.
     */
    public function endFooter()
    {
        echo "\n" . Html::endTag('div');
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options['class'] = isset($this->options['class']) ? 'box ' . $this->options['class'] : 'box';
        $this->headerOptions['class'] = isset($this->headerOptions['class']) ? 'box-header with-border ' . $this->headerOptions['class'] : 'box-header with-border';
        $this->bodyOptions['class'] = isset($this->bodyOptions['class']) ? 'box-body ' . $this->bodyOptions['class'] : 'box-body';
        $this->footerOptions['class'] = isset($this->footerOptions['class']) ? 'box-footer ' . $this->footerOptions['class'] : 'box-footer';
        if ($this->collapsed) {
            $this->options['class'] .= ' collapsed-box';
        }
    }

    /**
     * Start box-container
     * Only in header container!
     * Examples:
     * <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
     * <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove"><i class="fa fa-times"></i></button>.
     */
    public function beginTools()
    {
        echo "\n" . Html::beginTag('div', ['class' => 'box-tools pull-right btn-group']);
    }

    /**
     * End box-tools container.
     */
    public function endTools()
    {
        echo "\n" . Html::endTag('div');
    }
}
