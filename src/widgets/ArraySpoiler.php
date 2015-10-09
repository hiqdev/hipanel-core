<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use hipanel\helpers\ArrayHelper;
use yii\base\InvalidValueException;
use yii\base\Widget;
use yii\helpers\Html;

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
    /**
     * @var array|string|int Data to be proceeded
     */
    public $data;

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
     * @var string string to display on badge. Use sprintf format, the only var is count of additional elements
     */
    public $badgeFormat = "+%s";

    /**
     * @var array will be passed to javascript popover function as options.
     * @see http://getbootstrap.com/javascript/#popovers-options
     */
    public $popoverOptions = [];

    /**
     * @var array HTML attributes for badge tag
     */
    public $badgeOptions = ['class' => 'badge'];

    /**
     * @var string Group of popovers. Is used to close all other popovers in group, when new one is openning.
     */
    public $popoverGroup = 'main';

    public function init()
    {
        parent::init();

        if (is_string($this->data) || is_numeric($this->data)) {
            $this->data = ArrayHelper::csplit($this->data);
        }

        if (!is_array($this->data)) {
            throw new InvalidValueException('Input can not be processed as an array');
        }

        if (is_callable($this->formatter)) {
            $this->data = array_map($this->formatter, $this->data, array_keys($this->data));
        }
    }

    /**
     * Renders visible part
     */
    private function renderVisible()
    {
        $visible = [];
        for ($i = 0; $i < $this->visibleCount; $i++) {
            if (!count($this->data)) {
                break;
            }
            $visible[] = array_shift($this->data);
        }

        echo implode($this->delimiter, $visible);
    }

    /**
     * Renders spoiler
     */
    private function renderSpoiler()
    {
        if (count($this->data) === 0) return null;
        echo ' ';
        $options = array_merge([
            'data-popover-group' => $this->popoverGroup,
            'data-content' => implode($this->delimiter, $this->data),
            'id' => $this->id
        ], $this->badgeOptions);

        echo Html::tag('a', sprintf($this->badgeFormat, count($this->data)), $options);
        $this->getView()->registerJs("
             $('#{$this->id}').popover(" . json_encode($this->popoverOptions, JSON_FORCE_OBJECT) . ").on('show.bs.popover', function(e) {
                $('[data-popover-group=\"{$this->popoverGroup}\"]').not(e.target).popover('hide');
             });",
            \yii\web\View::POS_READY);
    }

    /**
     * Renders the all the widget
     */
    public function run()
    {
        $this->renderVisible();
        $this->renderSpoiler();
    }
}
