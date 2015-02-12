<?php
namespace frontend\modules\server\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class DiscountFormatter
 * Renders a button with popover to display current and upcoming discount
 *
 * @package app\modules\server\widgets
 */
class DiscountFormatter extends Widget {
    /**
     * @var string Tag name to be created
     */
    public $tagName = 'button';

    /**
     * @var float|string Current discount
     */
    public $current;

    /**
     * @var float|string Next discount
     */
    public $next;

    public function init () {
        parent::init();
        $this->current = floatval($this->current);
        $this->next    = floatval($this->next);
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
