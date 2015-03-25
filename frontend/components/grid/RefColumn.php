<?php

namespace frontend\components\grid;

use frontend\components\widgets\RefFilter;

class RefColumn extends DataColumn
{
    /** @var string gtype for Ref::getList */
    public $gtype;

    /** @inheritdoc */
    protected function renderFilterCellContent () {
        return RefFilter::widget([
            'attribute' => $this->getFilterAttribute(),
            'model'     => $this->grid->filterModel,
            'gtype'     => $this->gtype,
        ]);
    }
}
