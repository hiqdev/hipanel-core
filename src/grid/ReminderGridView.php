<?php

namespace hipanel\grid;

use Yii;

class ReminderGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'periodicity',
            'message',
            'next_time',

            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ];
    }
}
