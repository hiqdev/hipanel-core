<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use hipanel\widgets\RefFilter;

class RefColumn extends DataColumn
{
    /** @var string gtype for [[Ref::getList()]] */
    public $gtype;

    /**
     * @var array additional search options for [[Ref::getList()]]
     */
    public $findOptions = [];

    /** @inheritdoc */
    protected function renderFilterCellContent () {
        return RefFilter::widget([
            'attribute'   => $this->getFilterAttribute(),
            'model'       => $this->grid->filterModel,
            'gtype'       => $this->gtype,
            'findOptions' => $this->findOptions,
            'options'     => $this->filterInputOptions,
        ]);
    }
}
