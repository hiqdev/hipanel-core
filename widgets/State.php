<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use hipanel\base\Re;
use yii\helpers\ArrayHelper;

class State extends \hipanel\widgets\Label {

    /** @var model[] An array of ActiveRecord */

    public $model = [];

    /** @var $states[] which contains:
      * key - css class name which will be used to highlight label
      * values - states, that represent current CSS class
      * Examples
      * ~~~
      * ['info' => ['ok', 'expired']]
      * ~~~
     **/
    public $states = [];

    /** @var defoultStates[] inherits $states */

    public $defoultStates = [];

    public function run () {
        $state = $this->model->state;

        foreach ($this->defaultStates as $type => $states) {
            $possibleStates[$type] = ArrayHelper::merge($states, $this->states[$type] ? : []);
        }

        $this->states = ArrayHelper::merge($possibleStates, $this->states);

        foreach ($this->states as $class => $states) {
            if (in_array($state, $states)) { break; }
        }
        $class = $class ? : 'warning';

        $this->zclass   = $class;
        $this->label    = Re::l($this->model->state_label);
        parent::run();
    }
}
