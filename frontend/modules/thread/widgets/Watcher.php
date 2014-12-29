<?php
namespace frontend\modules\thread\widgets;

use yii\helpers\Html;
use yii\jui\Widget;

class Watcher extends Widget
{
    public $inView = true;

    public $watchers = [];

    public function init() {
        parent::init();
        if ($this->inView and is_array($this->watchers)) {
            print Html::beginTag('ul', ['class'=>'list-unstyled']);
            foreach ($this->watchers as $uid=>$username) {
                print Html::beginTag('li');
                    print Html::a($username, ['/client/client/view', 'id'=>$uid]);
                print Html::endTag('li');
            }
            print Html::endTag('ul');
        }

    }

    protected function cssClasses() {
        $t = mb_strtolower($this->type);
        $v = mb_strtolower($this->value);
        return ( array_key_exists($v, $this->rules[$t]) ) ? $this->rules[$t][$v] : $this->defaultCssClass;
    }

}