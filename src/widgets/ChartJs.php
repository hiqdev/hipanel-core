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

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * Class ChartJs.
 */
class ChartJs extends \dosamigos\chartjs\ChartJs
{
    /**
     * @var boolean|array Whether to draw the legend for the chart.
     *  - `false`: do not draw
     *  - `true`: legend with default options
     *  - `array`: array of options for the [[Html::tag()]]
     */
    public $legend = false;

    public function run()
    {
        parent::run();
        if ($this->legend !== false) {
            $this->legend = (array) $this->legend;
            $this->renderLegend();
        }
    }

    /**
     * Renders the legend block and registers proper CSS and JS.
     */
    public function renderLegend()
    {
        $options = ArrayHelper::merge([
            'id' => $this->options['id'] . '-legend',
            'class' => 'chart-legend',
        ], $this->legend);
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        echo Html::tag($tag, '', $options);
        $view = $this->getView();
        $view->registerJs("$('#{$options['id']}').html(chartJS_{$this->options['id']}.generateLegend());");
        $view->registerCss(
<<<CSS
.chart-legend li span {
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
CSS
        );
    }
}
