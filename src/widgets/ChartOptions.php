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

use hiqdev\yii2\daterangepicker\DateRangePicker;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class ChartOptions is used to render chart configurations buttons, register events listeners and reloads
 * the cart with the updated properties.
 */
class ChartOptions extends \yii\base\Widget
{
    /**
     * @var array The array of options that will be used in [[Html::beginForm]]
     * @see beginForm
     */
    public $form = [];

    /**
     * @var array The hidden inputs, that will be rendered inside of the HTML form.
     * Format:
     *  - `key`: the `name` of the input
     *  - `value`: array of 2 elements: `value` and `options`, that will be passed to [[Html::hiddenInput]] method
     *
     * Defaults to:
     * ```php
     * [
     *     'id' => ['value' => null, 'options' => []],
     *     'from' => ['value' => null, 'options' => []],
     *     'till' => ['value' => null, 'options' => []],
     * ]
     * ```
     *
     * @see initDefaults()
     */
    public $hiddenInputs = [];

    /**
     * @var string Template that is used to wrap the buttons.
     * The following tokens will be replaced by default when [[buildButtons()]]
     * is called: `{intervalSelect}`, `{aggregationSelect}`
     *
     * @see buildIntervalSelect
     * @see buildAggregationSelect
     * @see buildButtons
     */
    public $buttonsTemplate = '<div class="form-group">{intervalSelect}{aggregationSelect}</div>';

    /**
     * @var array This will be used together with [[buttonsTemplate]] to generate the final field HTML code of the
     * buttons. The keys are the token names in [[buttonsTemplate]], while the values are the corresponding HTML code.
     * Valid tokens include `{intervalSelect}` and `{aggregationSelect}`.
     * Note that you normally don't need to access this property directly as it is maintained by various
     * methods of this class.
     */
    public $buttonsParts = [];

    /**
     * @var array this array contains will be used on the client side to configure `jQuery.ajax()` call.
     * @see http://api.jquery.com/jquery.ajax/
     * @see registerClientScript
     */
    public $ajaxOptions = [];

    /**
     * @var array the options of the date picker plugin. Please, refer [[initDefaults()]] method to check the default
     * values of each option.
     * The widget is originally designed to use `omnilight/yii2-bootstrap-daterangepicker` extension, but it can be
     * overridden on demand.
     * @see https://github.com/omnilight/yii2-bootstrap-daterangepicker
     */
    public $pickerOptions = [];

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->getId(false) === null) {
            throw new InvalidConfigException('Manual assignment of id is required');
        }

        $this->initDefaults();
    }

    /**
     * Initializes default values for the widget properties.
     * @void
     */
    protected function initDefaults()
    {
        $id = $this->getId();

        $this->hiddenInputs = ArrayHelper::merge([
            'id' => ['value' => null, 'options' => []],
            'from' => ['value' => null, 'options' => []],
            'till' => ['value' => null, 'options' => []],
        ], $this->hiddenInputs);

        $this->form = ArrayHelper::merge([
            'action' => '#',
            'method' => 'post',
            'options' => [
                'class' => ['form-inline', $this->getId()],
            ],
        ], $this->form);

        $this->pickerOptions = ArrayHelper::merge([
            'class' => DateRangePicker::class,
            'name' => '',
            'options' => [
                'tag' => false,
                'id' => "{$id}-period-btn",
            ],
            'clientEvents' => [
                'apply.daterangepicker' => new JsExpression(/** @lang JavaScript */"
                    function (event, picker) {
                        var form = $(picker.element[0]).closest('form');
                        var span = form.find('#{$id}-period-btn span');

                        span.text(picker.startDate.format('ll') + ' - ' + picker.endDate.format('ll'));

                        form.find('input[name=from]').val(picker.startDate.format());
                        form.find('input[name=till]').val(picker.endDate.format());
                        form.trigger('change.updateChart');
                    }
                "),
                'cancel.daterangepicker' => new JsExpression(/** @lang JavaScript */"
                    function (event, picker) {
                        var form = $(event.element[0]).closest('form');
                        var span = form.find('#{$id}-period-btn span');

                        span.text(span.data('prompt'));

                        form.find('input[name=from]').val('');
                        form.find('input[name=till]').val('');
                        form.trigger('change.updateChart');
                    }
                "),
            ],
            'clientOptions' => [
                'showDropdowns' => true,
                'minDate' => new JsExpression("moment().year('2007')"),
                'maxDate' => new JsExpression("moment()"),
                'ranges' => [
                    Yii::t('hipanel', 'Current Month') => new JsExpression('[moment().startOf("month"), new Date()]'),
                    Yii::t('hipanel', 'Previous Month') => new JsExpression('[moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]'),
                    Yii::t('hipanel', 'Last 3 months') => new JsExpression('[moment().subtract(3, "month").startOf("month"), new Date()]'),
                    Yii::t('hipanel', 'Last 6 months') => new JsExpression('[moment().subtract(6, "month").startOf("month"), new Date()]'),
                    Yii::t('hipanel', 'Last year') => new JsExpression('[moment().subtract(1, "year").startOf("year"), new Date()]'),
                ],
            ],
        ], $this->pickerOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo $this->beginForm();
        echo $this->buildHiddenInputs();
        echo $this->buildButtons();
        echo $this->endForm();
        $this->registerClientScript();
    }

    /**
     * Begins the Html form.
     * @return string
     */
    protected function beginForm()
    {
        return Html::beginForm($this->form['action'], $this->form['method'], $this->form['options']);
    }

    /**
     * Builds hidden input according to the [[hiddenInptuts]] property.
     * @return string
     * @see hiddenInputs
     */
    protected function buildHiddenInputs()
    {
        $inputs = [];
        foreach ($this->hiddenInputs as $name => $options) {
            $value = ArrayHelper::remove($options, 'value');
            $inputs[] = Html::hiddenInput($name, $value, $options);
        }

        return implode("\n", $inputs);
    }

    /**
     * Builds buttons using [[buttonsTemplate]] and [[buttonsParts]].
     *
     * @return string
     * @see buttonsTemplates
     * @see buttonsParts
     */
    protected function buildButtons()
    {
        if (!isset($this->buttonsParts['{intervalSelect}'])) {
            $this->buttonsParts['{intervalSelect}'] = $this->buildIntervalSelect();
        }

        if (!isset($this->buttonsParts['{aggregationSelect}'])) {
            $this->buttonsParts['{aggregationSelect}'] = $this->buildAggregationSelect();
        }

        return strtr($this->buttonsTemplate, $this->buttonsParts);
    }

    /**
     * Builds the interval selection button, initializes the DatePicker widget according to [[pickerOptions]] array.
     *
     * @return string
     * @see pickerOptions
     */
    protected function buildIntervalSelect()
    {
        $interval = Yii::t('hipanel', 'Interval');

        $html = <<<HTML
<button type="button" class="btn btn-sm" id="{$this->getId()}-period-btn">
    <i class="fa fa-calendar"></i>
    <span data-prompt="$interval">$interval</span>
    <i class="fa fa-caret-down"></i>
</button>
HTML;
        $pickerOptions = $this->pickerOptions;
        $class = ArrayHelper::remove($pickerOptions, 'class');

        $html .= $class::widget($pickerOptions);

        return $html;
    }

    /**
     * Builds the dropdown for the aggregation selection.
     *
     * @return string
     */
    protected function buildAggregationSelect()
    {
        return Html::dropDownList('aggregation', 'month', [
            'day' => Yii::t('hipanel', 'Daily'),
            'week' => Yii::t('hipanel', 'Weekly'),
            'month' => Yii::t('hipanel', 'Monthly'),
        ], ['class' => 'form-control input-sm']);
    }

    /**
     * Ends the HTML form.
     *
     * @return string
     */
    protected function endForm()
    {
        return Html::endForm();
    }

    /**
     * Registers JS event listener to re-draw the chart upon demand.
     *
     * @void
     * @see ajaxOptions
     */
    protected function registerClientScript()
    {
        $id = $this->getId();
        $options = Json::encode($this->ajaxOptions);
        $this->getView()->registerJs(/** @lang JavaScript */"
            $('.{$id}').on('change.updateChart', function (event) {
                var defaultOptions = {
                    url: $(this).attr('action'),
                    data: $(this).serializeArray(),
                    type: 'post',
                    success: function (html) {
                        $('.{$id}-chart-wrapper').closest('.box').find('.box-body').html(html);
                    }
                };
                event.preventDefault();
                var options = $.extend(defaultOptions, $options, true)
                $.ajax(options);
            });
            ");
    }
}
