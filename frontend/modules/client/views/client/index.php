<?php
use yii\bootstrap\ButtonGroup;
use frontend\widgets\GridView;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;

$this->title = 'Clients';
$this->params['breadcrumbs'][] = $this->title;

$widgetButtonConfig = [
    'buttons'   => [
        [
            'label'     => Yii::t('app','Tariff'),
            'options'   => [
                'class'     => 'btn-xs' . ($tpl=='_tariff' ? ' active' : ''),
                'data-view' => '_tariff'
            ],
        ],
        [
            'label'     => Yii::t('app','Card'),
            'options'   => [
                'class'     => 'btn-xs' . ($tpl=='_card' ? ' active' : ''),
                'data-view' => '_card',
            ],
        ],
    ],
    'options'   => ['class'=>'change-view-button']
];

$widgetIndexConfig = [
    'dataProvider'  => $dataProvider,
    'filterModel'   => $searchModel,
    'columns'       => [
        [
            'class'         => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute'     => 'login',
            'label'         => Yii::t('app', 'Client'),
            'value'         => function ($data) {
                return  Html::a($data->login, ['/client/client/view','id'=>$data->id]);
            },
            'format'        => 'html',
            'filterInputOptions'=> ['id' => 'id'],
            'filter'            => frontend\widgets\Select2::widget([
                'attribute'     =>'id',
                'model'         => $searchModel,
                'options'       => [
                    'id'            => 'id',
                ],
                'settings'      => [
                    'allowClear'    => true,
                    'placeholder'   =>'Type name ...',
                    'width'         =>'100%',
                    'triggerChange' => true,
                    'minimumInputLength' => 3,
                    'ajax'          => [
                        'url'           => yii\helpers\Url::to(['client-all-list']),
                        'dataType'      => 'json',
                        'data'          => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results'       => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                        var id=$(elem).val();
                        $.ajax("' . yii\helpers\Url::to(['client-all-list']) . '?id=" + id, {
                            dataType: "json"
                        }).done(function(data) {
                            callback(data.results);
                        });
                    }')
                ],
            ]),
        ],
        [
            'attribute'     => 'seller_id',
            'label'         => Yii::t('app','Seller'),
            'value'         => function ($data) {
                return  Html::a($data->seller, ['/client/client/view','id'=>$data->seller_id]);
            },
            'format'        => 'html',
            'filterInputOptions'=> ['id' => 'seller_id'],
            'filter'            => frontend\widgets\Select2::widget([
                'attribute'     =>'seller_id',
                'model'         => $searchModel,
                'options'       => [
                    'id'            => 'seller_id',
                ],
                'settings'      => [
                    'allowClear'    => true,
                    'placeholder'   =>'Type name ...',
                    'width'         =>'100%',
                    'triggerChange' => true,
                    'minimumInputLength' => 3,
                    'ajax'          => [
                        'url'           => yii\helpers\Url::to(['seller-list']),
                        'dataType'      => 'json',
                        'data'          => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results'       => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                        var id=$(elem).val();
                        $.ajax("' . yii\helpers\Url::to(['seller-list']) . '?id=" + id, {
                            dataType: "json"
                        }).done(function(data) {
                            callback(data.results);
                        });
                    }')
                ],
            ]),
        ],
        'email',
    ],
];

switch ($tpl) {
    case '_card':
        $widgetIndexConfig['columns'] = \yii\helpers\ArrayHelper::merge($widgetIndexConfig['columns'], [
            [
                'attribute' => 'name',
                'label'     => Yii::t('app','Name'),
            ],
            [
                'attribute' => 'type',
                'label'     => Yii::t('app','Type'),
            ],
            [
                'attribute' => 'state',
                'label'     => Yii::t('app','State'),
            ],
            [
                'label'     => Yii::t('app','Balance'),
                'format'    => 'html',
                'value'     => function ($data) {
                    return Yii::t('app','Balance') . ": " . HTML::tag('span', $data->balance, $data->balance < 0 ? 'color="red"' : '') .
                    "<br/>" .
                    Yii::t('app','Credit') .": ". HTML::a($data->credit, ['/client/client/set-credit','id'=>$data->id]);
                },
            ],
            [
                'attribute' => 'create_time',
                'label'     => Yii::t('app','Create date'),
                'format'    => ['date', 'php:Y-m-d'],
            ],
        ]);
        break;
    case '_tariff':
        $widgetIndexConfig['columns'] = \yii\helpers\ArrayHelper::merge($widgetIndexConfig['columns'], [
                [
                'attribute'     =>'tariff_name',
                'format'        => 'html',
                'filter'        => Html::dropDownList(
                    'tariff_name',
                    ['1'],
                    [1=>1,2=>2],
                    [
                        'class'     => 'form-control',
                        'prompt'    => Yii::t('app', 'BACKEND_PROMPT_STATUS'),
                ]
                ),
            ],
            [
                'attribute'     => 'state',
            ],
            [
                'attribute'     => 'create_time',
                'format'        => ['date', 'Y-m-d'],
                'filter'        => DatePicker::widget([
                    'name'          => 'create_time',
                    'options'       => [
                        'class'         => 'form-control',
                    ],
                    'clientOptions' => [
                        'dateFormat'    => 'dd.mm.yy',
                    ],
                ]),
            ],
       ]);
}

$widgetIndexConfig['columns'] = \yii\helpers\ArrayHelper::merge($widgetIndexConfig['columns'], [
    [
        'class'         => 'yii\grid\ActionColumn',
        'template'      => '{view} {update} {block} {delete}',
        'buttons'       => [
            'view'          => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view','id'=>$model['id']]);
            },
            'block'         => function ($url, $model, $key) {
                if ($model->state == 'ok') return Html::a('<span class="glyphicon glyphicon-log-in"></span>', ['enable-block','id'=>$model['id']]);
                elseif ($model->state == 'blocked') return Html::a('<span class="glyphicon glyphicon-log-out"></span>', ['disable-block','id'=>$model['id']]);
            },
            'delete'        => function ($url, $model, $key) {
                if ($model->state != 'deleted') return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete','id'=>$model['id']]);
            },
        ],
    ],
]);

?>
<div class="row">
    <div class="col-md-1 col-md-offset-11" style="margin: 10px">
        <?= ButtonGroup::widget($widgetButtonConfig); ?>
    </div>
</div>
<?= GridView::widget($widgetIndexConfig); ?>
<?php $this->registerJs("
    $( document ).on('click', '.change-view-button button', function() {
        var view = $(this).data('view');

        if ( view == '_tariff' )
            location.replace('".Url::toRoute(['index','tpl'=>'_tariff'])."');
        else
            location.replace('".Url::toRoute(['index','tpl'=>'_card'])."');
    });
", View::POS_END, 'view-options'); ?>
