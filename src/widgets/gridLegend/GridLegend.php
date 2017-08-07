<?php

namespace hipanel\widgets\gridLegend;

use Yii;
use yii\base\Widget;

class GridLegend extends Widget
{
    /**
     * @var GridLegendInterface
     */
    public $legendItem;

    /**
     * @param GridLegendInterface $legendItem
     * @param array $config
     * @return string
     */
    public static function create(GridLegendInterface $legendItem)
    {
        return Yii::createObject(['class' => static::class, 'legendItem' => $legendItem]);
    }

    public function gridRowOptions()
    {
        foreach ($this->legendItem->items() as $item) {
            if (isset($item['rule']) && boolval($item['rule']) === true) {
                return ['style' => "background-color: {$item['color']} !important;"];
            }
        }
    }

    public function getColor($item)
    {
        return isset($item['color']) ? $item['color'] : '#ffffff';
    }

    public function getLabel($item)
    {
        return isset($item['label']) ? $item['label'] : Yii::t('hipanel', 'Empty');
    }

    public function run()
    {
        return $this->render('Lagend', ['items' => $this->legendItem->items()]);
    }
}
