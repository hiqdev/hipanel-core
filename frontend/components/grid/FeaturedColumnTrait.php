<?php

namespace frontend\components\grid;

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Trait FeaturedColumnTrait
 */
trait FeaturedColumnTrait
{
    /**
     * @var string Popover text
     */
    public $popover;

    /**
     * @var array Options to popover()
     */
    public $popoverOptions = [
        'placement'     => 'bottom',
        'selector'      => 'a',
    ];

    /**
     * @var string name for filter input
     */
    public $filterAttribute;

    /** @inheritdoc */
    public function init () {
        parent::init();
        $this->registerClientScript();
    }

    public function registerClientScript () {
        $view = \Yii::$app->getView();
        $ops = Json::encode($this->popoverOptions);
        $view->registerJs("$('#{$this->grid->id} thead th[data-toggle=\"popover\"]').popover($ops);", \yii\web\View::POS_READY);
    }

    public function renderHeaderCellContent () {
        $this->headerOptions = ArrayHelper::merge($this->headerOptions,[
            'data-toggle'  => 'popover',
            'data-trigger' => 'hover',
            'data-content' => $this->popover,
        ]);
        return parent::renderHeaderCellContent();
    }

    public function renderFilterCellContent () {
        $save = $this->attribute;
        if ($this->filterAttribute) $this->attribute = $this->filterAttribute;
        $out = parent::renderFilterCellContent();
        $this->attribute = $save;
        return $out;
    }

}
