<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use ArrayObject;
use hipanel\helpers\ArrayHelper;
use Yii;
use yii\base\InvalidValueException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\web\View;

/**
 * ArraySpoiler displays limited count of array's elements and hides all others behind a spoiler (badge)
 *
 * The following example will show first two elements of array concatenated with semicolon and bold-narrowed
 *
 * ~~~php
 * ArraySpoiler::widget([
 *      'data' => ['10.0.0.1', '10.0.0.2', '10.0.0.3', '10.0.0.4'],
 *      'formatter' => function ($v) {
 *          return Html::tag('b', $v);
 *      },
 *      'visibleCount' => 2,
 *      'delimiter'     => '; ',
 *      'popoverOptions' => ['html' => true]
 * ]);
 * ~~~
 *
 * Also widget can split string in 'data' field into array by comma symbol.
 *
 * @author SilverFire <d.naumenko.a@gmail.com>
 */
class ArraySpoiler extends Widget
{
    const MODE_POPOVER = 'popover';
    const MODE_SPOILER = 'spoiler';

    /**
     * @var string The mode. See `MODE_` constants for list of available constants.
     * Default - [[MODE_POPOVER]]
     */
    public $mode;

    /**
     * @var array|string|int Data to be proceeded
     */
    public $data;

    /**
     * @var string the template that is used to arrange show items, activating button and hidden inputs
     * The following tokens will be replaced when [[render()]] is called: `{shown}`, `{button}`, `{hidden}`.
     */
    public $template = "{visible}\n{button}\n{hidden}";

    /**
     * @var array different parts of the field (e.g. show, hidden). This will be used together with
     * [[template]] to generate the final field HTML code. The keys are the token names in [[template]],
     * while the values are the corresponding HTML code. Valid tokens include `{visible}`, `{button}` and `{hidden}`.
     * Note that you normally don't need to access this property directly as
     * it is maintained by various methods of this class.
     */
    public $parts = [];

    /**
     * @var callable the function will be called for every element to format it.
     * Gets two arguments - value and key
     */
    public $formatter = null;

    /**
     * @var int count of elements, that are visible out of spoiler
     */
    public $visibleCount = 1;

    /**
     * @var string delimiter to join elements
     */
    public $delimiter = ', ';

    /**
     * @var array|string When string - will be auto-converted to an array. Array will be passed to [[Html::tag()]]
     * as option argument. Special options that will be extracted:
     *  - label - string
     *  - i18n - string|bool whether to pass `label` through [[Yii::t()]]. String will be used as dictionary name.
     * Available substitutions: count
     *  - tag - html tag that will be rendered (default - `span`)
     *
     * For other special options see [[renderSpoiler()]] and [[renderPopover()]] methods.
     */
    public $button = "+{count}";

    /**
     * @var array configuration will be passed to [[Html::tag()]] as options argument
     */
    public $hidden = [];

    public function init()
    {
        parent::init();

        if (is_string($this->data) || is_numeric($this->data)) {
            $this->data = StringHelper::explode($this->data);
        } elseif ($this->data === null) {
            $this->data = [];
        }

        if (empty($this->mode)) {
            $this->mode = static::MODE_POPOVER;
        }

        if (!is_callable([$this, 'renderButton' . Inflector::id2camel($this->mode)])) {
            throw new InvalidValueException('Do not know, how to render button of this type');
        }

        if (is_string($this->button)) {
            $this->button = ['label' => $this->button];
        }

        $this->button = ArrayHelper::merge([
            'tag' => 'a',
            'id' => $this->id,
        ], $this->button);

        if (!is_array($this->data)) {
            throw new InvalidValueException('Input can not be processed as an array');
        }

        if (is_callable($this->formatter)) {
            $this->data = array_map($this->formatter, $this->data, array_keys($this->data));
        }

        if (is_callable($this->button['label'])) {
            $this->button['label'] = call_user_func($this->button['label'], $this);
        }
    }

    /**
     * Method returns first [[visibleCount]] items from [[data]]
     * @return array
     */
    protected function getVisibleItems() {
        if (count($this->data) <= $this->visibleCount) {
            return $this->data;
        }
        $visible = [];
        $iterator = (new ArrayObject($this->data))->getIterator();
        while (count($visible) < $this->visibleCount && $iterator->valid()) {
            $visible[] = $iterator->current();
            $iterator->next();
        }

        return $visible;
    }

    /**
     * Method returns all items from [[data]], skipping first [[visibleCount]]
     * @return array
     */
    protected function getSpoiledItems() {
        if (count($this->data) <= $this->visibleCount) {
            return [];
        }
        $spoiled = [];
        $iterator = (new ArrayObject($this->data))->getIterator();
        $iterator->seek($this->visibleCount);
        while ($iterator->valid()) {
            $spoiled[] = $iterator->current();
            $iterator->next();
        }
        return $spoiled;
    }

    /**
     * Renders visible part of spoiler
     */
    private function renderVisible()
    {
        $this->parts['{visible}'] = implode($this->delimiter, $this->getVisibleItems());
    }

    /**
     * Renders spoiled items. Uses [[$this->mode]] value to run proper renderer
     */
    private function renderSpoiled()
    {
        if (count($this->getSpoiledItems()) === 0) {
            $this->parts['{button}'] = '';
            return;
        }

        $method = 'renderButton' . Inflector::id2camel($this->mode);
        call_user_func([$this, $method]);
    }

    /**
     * Renders spoiled items. Uses [[$this->mode]] value to run proper renderer
     */
    private function renderHidden()
    {
        if (count($this->getSpoiledItems()) === 0) {
            $this->parts['{hidden}'] = '';
            return;
        }

        $method = 'renderHidden' . Inflector::id2camel($this->mode);
        call_user_func([$this, $method]);
    }

    /**
     * Renders a popover-activating button.
     * Additional special options, that will be extracted from [[$this->button]]:
     *  - `data-popover-group` - Group of popovers. Is used to close all other popovers in group, when new one is opening. Default: 'main'
     *  - `popoverOptions` - Array of options that will be passed to `popover` JS call. Refer to bootstrap docs.
     *
     * @see http://getbootstrap.com/javascript/#popovers-options
     */
    protected function renderButtonPopover()
    {
        $options = ArrayHelper::merge([
            'data-popover-group' => 'main',
            'data-content' => $this->renderHiddenPopover(),
            'class' => 'badge',
            'popoverOptions' => [],
        ], $this->button);

        $label = $this->getButtonLabel(ArrayHelper::remove($options, 'label'));

        $this->getView()->registerJs("
             $('#{$this->id}').popover(" . Json::htmlEncode(ArrayHelper::remove($options, 'popoverOptions')) . ").on('show.bs.popover', function(e) {
                $('[data-popover-group=\"{$options['data-popover-group']}\"]').not(e.target).popover('hide');
             });
         ", View::POS_READY);

        $this->parts['{button}'] = Html::tag(ArrayHelper::remove($options, 'tag'), $label, $options);
    }


    /**
     * Renders a popover-activated hidden part.
     * Actually is does not render anything. Sets `{hidden}` to an empty string and returns the value of spoiled items
     * @return string
     */
    public function renderHiddenPopover()
    {
        $this->parts['{hidden}'] = '';
        return implode($this->delimiter, $this->getSpoiledItems());
    }

    /**
     * Renders a button for spoiler activator.
     *
     * Additional special options, that will be extracted from [[$this->button]]:
     *  - `data-popover-group` - Group of popovers. Is used to close all other popovers in group, when new one is opening. Default: 'main'
     *
     * @see http://getbootstrap.com/javascript/#popovers-options
     */
    protected function renderButtonSpoiler()
    {
        $options = ArrayHelper::merge([
            'role' => 'button',
            'data-spoiler-group' => 'main',
            'data-spoiler-toggle' => true,
            'data-target' => $this->button['id'] . '-body'
        ], $this->button);

        $label = $this->getButtonLabel(ArrayHelper::remove($options, 'label'));

        $this->parts['{button}'] = Html::tag(ArrayHelper::remove($options, 'tag'), $label, $options);
        $this->getView()->registerJs("
             $('[data-spoiler-toggle]').click(function (e) {
                $('#'+$(this).data('target')).toggle();
                $('[data-spoiler-group=\"{$options['data-spoiler-group']}\"]').not($(this)).trigger('hide');
             }).on('hide', function () {
                $('#'+$(this).data('target')).hide();
             })",
            View::POS_READY);
    }

    /**
     * Renders a spoiler-button-activated hidden part
     */
    public function renderHiddenSpoiler()
    {
        $options = ArrayHelper::merge([
            'id' => $this->button['id'] . '-body',
            'tag' => 'span',
            'value' => implode($this->delimiter, $this->getSpoiledItems()),
            'class' => 'collapse',
            'data-spoiler-body' => true,
        ], $this->hidden);

        $this->parts['{hidden}'] = Html::tag(ArrayHelper::remove($options, 'tag'), ArrayHelper::remove($options, 'value'), $options);
    }

    /**
     * Returns the button label.
     *
     * @param $label string|\Closure
     * @return mixed|string
     */
    public function getButtonLabel($label) {
        if ($label instanceof \Closure) {
            $label = call_user_func($label, $this);
        } else {
            $label = Yii::t('app', $label, ['count' => count($this->getSpoiledItems())]);
        }

        return $label;
    }


    /**
     * Renders the all the widget
     */
    public function run()
    {
        if (!isset($this->parts['{visible}'])) {
            $this->renderVisible();
        }
        if (!isset($this->parts['{button}'])) {
            $this->renderSpoiled();
        }
        if (!isset($this->parts['{hidden}'])) {
            $this->renderHidden();
        }

        echo strtr($this->template, $this->parts);
    }
}
