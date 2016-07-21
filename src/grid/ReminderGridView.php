<?php

namespace hipanel\grid;

use Yii;

class ReminderGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'object_id' => [
                'class' => MainColumn::class
            ],
            'message',

            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ];
    }
}
