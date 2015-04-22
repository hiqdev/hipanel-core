<?php

namespace frontend\modules\hosting\grid;

use frontend\components\grid\ActionColumn;
use frontend\components\grid\BoxedGridView;
use frontend\components\grid\RefColumn;
use frontend\components\grid\MainColumn;
use frontend\components\grid\EditableColumn;
use frontend\modules\server\grid\ServerColumn;
use frontend\modules\hosting\widgets\db\State;
use Yii;

class DbGridView extends BoxedGridView
{
    static public function defaultColumns () {
        return [
            'name'        => [
                'class'           => MainColumn::className(),
                'filterAttribute' => 'name_like'
            ],
            'state'       => [
                'class'  => RefColumn::className(),
                'format' => 'raw',
                'value'  => function ($model) {
                    return State::widget(compact('model'));
                },
                'gtype'  => 'state,db',
            ],
            'server'      => [
                'class' => ServerColumn::className()
            ],
            'service_ip'  => [
                'filter' => false
            ],
            'description' => [
                'class'   => EditableColumn::className(),
                'filter'  => true,
                'popover' => Yii::t('app', 'Make any notes for your convenience'),
                'action'  => ['set-description'],
            ],
            'password'    => [
                'class'   => EditableColumn::className(),
                'filter'  => true,
                'popover' => Yii::t('app', 'Change the DB password'),
                'action'  => ['set-password'],
                'value'   => function () {
                    return Yii::t('app', 'Change password');
                }
            ],
            'actions'     => [
                'class'    => ActionColumn::className(),
                'template' => '{view} {update} {truncate} {delete}'
            ]
        ];
    }
}
