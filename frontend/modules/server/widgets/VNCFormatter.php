<?php
namespace frontend\modules\server\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class VNCFormatter extends Widget {
    public $model;

    public function init () {
        parent::init();
    }

    public function run () {
        $this->getView()->registerJs("$('.discount-popover').popover();", \yii\web\View::POS_READY, 'discount-popover');

        return Html::tag($this->tagName,
            \Yii::$app->formatter->asPercent($this->current/100),
            [
                'title'        => \Yii::t('app', 'Next discount'),
                'class'        => 'btn btn-default btn-xs discount-popover',
                'data-trigger' => 'focus',
                'data-content' => \Yii::$app->formatter->asPercent($this->next/100),
            ]);
    }
}
