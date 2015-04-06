<?php

namespace frontend\modules\domain\grid;

use frontend\components\grid\BoxedGridView;
use frontend\components\grid\RefColumn;
use frontend\components\grid\MainColumn;
use frontend\components\grid\SwitchColumn;
use frontend\components\grid\EditableColumn;
use frontend\components\widgets\ArraySpoiler;
use frontend\modules\domain\widgets\State;
use frontend\modules\domain\widgets\Expires;
use Yii;

class DomainGridView extends BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'domain' => [
                'class'                 => MainColumn::className(),
            ],
            'state' => [
                'class'                 => RefColumn::className(),
                'format'                => 'raw',
                'gtype'                 => 'state,domain',
                'value'                 => function ($model) {
                    return State::widget(compact('model'));
                }
            ],
            'whois_protected' => [
                'class'                 => SwitchColumn::className(),
                'popover'               => 'WHOIS protection',
                'pluginOptions'         => [
                    'onColor'   => 'success',
                    'offColor'  => 'warning',
                ],
            ],
            'is_secured' => [
                'class'                 => SwitchColumn::className(),
                'attribute'             => 'is_secured',
                'popover'               => Yii::t('app', 'Protection from transfer'),
            ],
            'note' => [
                'class'                 => EditableColumn::className(),
                'attribute'             => 'note',
                'filter'                => true,
                'popover'               => Yii::t('app','Make any notes for your convenience'),
                'action'                => ['set-note'],
            ],
            'created_date' => [
                'attribute'             => 'created_date',
                'format'                => 'date',
                'filter'                => false,
                'contentOptions'        => ['class' => 'text-nowrap'],
            ],
            'expires' => [
                'format'                => 'raw',
                'filter'                => false,
                'headerOptions'         => ['style' => 'width:1em'],
                'value'                 => function ($model) {
                    return Expires::widget(compact('model'));
                },
            ],
            'autorenewal' => [
                'class'                 => SwitchColumn::className(),
                'label'                 => 'Autorenew',
                'popover'               => 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
                'pluginOptions'         => [
                    'onColor'   => 'info',
                ],
            ],
            'nameservers' => [
                'format'                => 'raw',
                'value'                 => function ($model) {
                    return ArraySpoiler::widget(['data' => $model->nameservers]);
                },
            ],
        ];
    }
}
