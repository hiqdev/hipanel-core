<?php
declare(strict_types=1);

namespace hipanel\grid;

class RefGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'attribute' => 'name',
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
            'label' => [
                'attribute' => 'label',
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ]);
    }
}
