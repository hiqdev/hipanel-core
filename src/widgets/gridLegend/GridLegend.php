<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

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
     * @return self
     */
    public static function create(GridLegendInterface $legendItem)
    {
        return new self(['legendItem' => $legendItem]);
    }

    public function gridRowOptions()
    {
        foreach ($this->legendItem->items() as $item) {
            if (!isset($item['columns']) && isset($item['rule']) && (bool) $item['rule'] === true) {
                return ['style' => "background-color: {$this->getColor($item)} !important;"];
            }
        }

        return [];
    }

    public function gridColumnOptions($column)
    {
        foreach ($this->legendItem->items() as $item) {
            if (isset($item['columns']) && \in_array($column, $item['columns'], true) && (bool) $item['rule'] === true) {
                return ['style' => "background-color: {$this->getColor($item)} !important;"];
            }
        }

        return [];
    }

    public function getColor($item)
    {
        return $item['color'] ?? 'transparent';
    }

    public function getLabel($item)
    {
        return isset($item['label']) ? (\is_array($item['label']) ? Yii::t($item['label'][0], $item['label'][1]) : $item['label']) : Yii::t('hipanel', 'Empty');
    }

    public function run()
    {
        return $this->render('Legend', ['items' => $this->legendItem->items()]);
    }
}
