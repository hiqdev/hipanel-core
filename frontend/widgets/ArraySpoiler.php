<?php
/**
 * Created by PhpStorm.
 * User: SilverFire
 * Date: 21.01.2015
 * Time: 11:13
 */

namespace frontend\widgets;

use yii\base\InvalidValueException;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * ArraySpoiler displays limited count of array's elements and hides all others behind a spoiler (badge)
 *
 * The following example will show first two elements of array concatenated with semicolon and bold-narrowed
 *
 *
 * ~~~php
 * ArraySpoiler::widget([
 *      'data' => ['10.0.0.1', '10.0.0.2', '10.0.0.3', '10.0.0.4'],
 *      'formatter' => function ($v) {
 *          return Html::tag('b', $v);
 *      },
 *      'visible_count' => 2,
 *      'delimiter'     => '; ',
 *      'popover_options' => ['html' => true]
 * ]);
 * ~~~
 *
 * Also widget can split string in 'data' field into array by comma symbol.
 *
 * @package frontend\widgets
 * @author SilverFire <d.naumenko.a@gmail.com>
 */
class ArraySpoiler extends Widget
{
    /**
     * @var array|string|int Data to be proceeded
     */
    public $data;

    /**
     * @var callable the function will be called for every element to format it. Accepts the only argument - value
     */
    public $formatter       = null;

    /**
     * @var int count of elements, that are visible out of spoiler
     */
    public $visible_count   = 1;

    /**
     * @var string delimiter to join elements
     */
    public $delimiter       = ', ';

    /**
     * @var string string to display on badge. Use sprintf format, the only var is count of additional elements
     */
    public $badge_format    = "+%s";

    /**
     * @var array will be passed to javascript popover function as options.
     * @see http://getbootstrap.com/javascript/#popovers-options
     */
    public $popover_options = [];

    /**
     * @var array HTML attributes for badge tag
     */
    public $badge           = ['class' => 'badge'];

    public function init()
    {
        parent::init();

        if (is_string($this->data) || is_numeric($this->data)) {
            $this->data = $this->parse_csplit();
        }

        if (!is_array($this->data)) {
            throw new InvalidValueException('Input can not be processed as an array');
        }

        if (is_callable($this->formatter)) {
            $this->data = array_map($this->formatter, $this->data);
        }
    }

    /**
     * Parses data, exploding the string by comma, trying to create array
     * @return array
     */
    private function parse_csplit()
    {
        $res = [];
        foreach (explode(',', $this->data) as $k => $v) {
            $v = trim($v);
            if (strlen($v)) {
                array_push($res, $v);
            }
        }

        return $res;
    }

    /**
     * Renders visible part
     */
    private function renderVisible()
    {
        $visible = [];
        for ($i = 0; $i < $this->visible_count; $i++) {
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
        if (!count($this->data)) return;
        echo ' ';
        $options = array_merge([
            'data-content' => Html::decode(implode($this->delimiter, $this->data)),
            'id' => $this->id
        ], $this->badge);

        echo Html::tag('a', sprintf($this->badge_format, count($this->data)), $options);
        $this->getView()->registerJs("$('#{$this->id}').popover(" . json_encode($this->popover_options, JSON_FORCE_OBJECT) . ");", \yii\web\View::POS_READY);
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