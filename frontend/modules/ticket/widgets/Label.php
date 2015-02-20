<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 12/19/14
 * Time: 2:56 PM
 */

namespace frontend\modules\thread\widgets;

use yii\helpers\Html;
use yii\jui\Widget;

class Label extends Widget
{
    public $rules = [
        'priority'  => ['medium'=>'info', 'high'=>'warning'],
        'state'     => ['opened'=>'success'],
    ];

    public $label;

    public $value;

    public $type;

    public $defaultCssClass = 'default';

    public function init() {
        parent::init();
        print Html::tag('span', $this->label, ['class'=>'label label-'.$this->cssClasses()]);
    }

    protected function cssClasses() {
        $t = mb_strtolower($this->type);
        $v = mb_strtolower($this->value);
        return ( array_key_exists($v, $this->rules[$t]) ) ? $this->rules[$t][$v] : $this->defaultCssClass;
    }

}