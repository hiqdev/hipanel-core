<?
use frontend\components\widgets\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'      => [
        // ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'yii\grid\CheckboxColumn',
            // you may configure additional properties here
        ],
        'name',
        'email',
        'balance',
        'credit',
        [
            'attribute' => 'tariff_name',
            'format'    => 'html',
            'filter'    => Html::dropDownList('tariff_name',
                ['1'],
                [1 => 1, 2 => 2],
                [
                    'class'  => 'form-control',
                    'prompt' => Yii::t('app', 'BACKEND_PROMPT_STATUS')
                ])
        ],
        [
            'attribute' => 'state',
        ],
        [
            'attribute' => 'create_time',
            'format'    => ['date', 'php:d.m.Y H:i'],
            'filter'    => DatePicker::widget([
                'name'          => 'create_time',
                'options'       => [
                    'class' => 'form-control'
                ],
                'clientOptions' => [
                    'dateFormat' => 'dd.mm.yy',
                ]
            ])
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons'  => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model['id']]);
                },
            ],
        ],
    ],
]); ?>
