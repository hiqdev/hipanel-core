<?php
use frontend\components\Re;
use frontend\widgets\GridView;
use frontend\modules\object\widgets\RequestState;
use frontend\widgets\Pjax;
use frontend\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var frontend\components\View $this
 */

$this->title                   = Yii::t('app', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute'          => 'client_id',
            'value'              => function ($model) {
                return Html::a($model->client, ['/client/client/view', 'id' => $model->client_id]);
            },
            'format'             => 'html',
            'filterInputOptions' => ['id' => 'client_id'],
            'label'              => Yii::t('app', 'Client'),
            'filter'             => Select2::widget([
                'attribute' => 'client_id',
                'model'     => $searchModel,
                'url'       => Url::to(['/client/client/client-all-list'])
            ]),
        ],
        [
            'attribute' => 'device_like',
            'label'     => Yii::t('app', 'Server'),
            'value'     => function ($model) {
                return $model->device;
            },
            'filter'    => Select2::widget([
                'attribute' => 'device_id',
                'model'     => $searchModel,
                'url'       => Url::to(['/server/server/list'])
            ]),
        ],
        [
            'attribute' => 'login_like',
            'format'    => 'text',
            'value'     => function ($model) {
                return $model->login;
            }
        ],
        [
            'attribute' => 'state',
            'format'    => 'raw',
            'value'     => function ($model) {
                return RequestState::widget([
                    'model' => $model
                ]);
            },
            'filter'    => Html::activeDropDownList($searchModel, 'state', $states, [
                'class'  => 'form-control',
                'prompt' => Yii::t('app', '--'),
            ]),
        ],
        [
            'attribute' => 'type',
            'value'     => function ($model) {
                return Re::l($model->type_label);
            },
            'filter'    => Html::activeDropDownList($searchModel, 'type', $types, [
                'class'  => 'form-control',
                'prompt' => Yii::t('app', '---')
            ])
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons'  => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id]);
                },
            ],
        ],
    ],
]);

Pjax::end();