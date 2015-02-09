<?php
namespace frontend\modules\thread\widgets;

use yii\helpers\Html;
use yii\jui\Widget;
use common\components\Lang;

class Topic extends Widget
{
    public $topic;

   private function _getColor($item) {
       $colors = [
           'general' => 'label-default',
           'technical' => 'label-primary',
           'domain' => 'label-success',
           'financial' => 'label-warning',
       ];
       if (array_key_exists($item, $colors))
           return $colors[$item];
       else
           return reset($colors);
   }

    public function init() {
        parent::init();
        if ($this->topic) {
            $html = '';
            $html .= '<ul class="list-inline">';
                foreach ($this->topic as $item=>$label) {
                    $html .= Html::tag('li', '<span class="label '.$this->_getColor($item).'">'.Lang::l($label).'</span>');
                }
            $html .= '</ul>';
            print $html;
        }
    }
}