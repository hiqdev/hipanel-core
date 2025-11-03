<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use Yii;
use yii\grid\Column;
use yii\helpers\Html;

/**
 * Class ColspanColumn.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ColspanColumn extends DataColumn
{
    /** @var Column[] */
    public array $columns;

    public function init()
    {
        $this->headerOptions['colspan'] = count($this->columns);
        parent::init();
    }

    public function renderDataCell($model, $key, $index)
    {
        $cells = [];

        foreach ($this->createSubColumns() as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }

        return implode('', $cells);
    }

    public function renderFilterCell()
    {
        $cells = [];

        foreach ($this->createSubColumns() as $column) {
            $cells[] = Html::tag('td', $column->renderHeaderCellContent(), $this->filterOptions);
        }

        return implode('', $cells);
    }

    /**
     * @return Column[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function createSubColumns()
    {
        foreach ($this->columns as $id => $column) {
            if ($column instanceof Column) {
                continue;
            }
            $this->columns[$id] = Yii::createObject(array_merge([
                'class' => $this->grid->dataColumnClass ?? DataColumn::class,
                'grid' => $this->grid,
            ], $column));
        }

        return $this->columns;
    }
}
