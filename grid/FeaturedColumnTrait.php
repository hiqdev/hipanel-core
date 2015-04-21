<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Trait FeaturedColumnTrait
 * Adds popover and filterAttribute
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

    /** @var array default options for someOptions */
/*
    public $defaultOptions = [];
*/

    /** @inheritdoc */
    public function init () {
        parent::init();
        if ($this->hasProperty('defaultOptions')) foreach ($this->defaultOptions as $k => $v) {
            $this->{$k} = ArrayHelper::merge($v,$this->{$k});
        };
        $this->registerClientScript();
    }

    public function registerClientScript () {
        $view = \Yii::$app->getView();
        $ops = Json::encode($this->popoverOptions);
        $view->registerJs("$('#{$this->grid->id} thead th[data-toggle=\"popover\"]').popover($ops);", \yii\web\View::POS_READY);
    }

    public function renderHeaderCellContent () {
        if ($this->popover) {
            $this->headerOptions = ArrayHelper::merge($this->headerOptions,[
                'data-toggle'  => 'popover',
                'data-trigger' => 'hover',
                'data-content' => $this->popover,
            ]);
        };
        return parent::renderHeaderCellContent();
    }

    public function getFilterAttribute () {
        return $this->filterAttribute ?: $this->attribute;
    }

    /// XXX better change yii
    protected function renderFilterCellContent () {
        if ($this->hasProperty('attribute')) {
            $save = $this->attribute;
            $this->attribute = $this->getFilterAttribute();
        };
        $out = parent::renderFilterCellContent();
        if ($this->hasProperty('attribute')) {
            $this->attribute = $save;
        };
        return $out;
    }

}
