<?php

namespace hipanel\grid;

use Yii;

class ReminderGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'periodicity' => [
                'filter' => false,
            ],
            'message' => [
                'filter' => false,
            ],
            'next_time' => [
                'filter' => false,
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ];
    }
}
