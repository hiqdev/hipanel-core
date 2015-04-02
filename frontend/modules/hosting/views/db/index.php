<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\RefColumn;
use frontend\modules\client\grid\ClientColumn;
use frontend\components\widgets\GridActionButton;
use frontend\components\widgets\Pjax;
use frontend\modules\client\grid\ResellerColumn;
use frontend\modules\hosting\widgets\db\State;
use frontend\modules\server\grid\ServerColumn;
use yii\helpers\Html;

$this->title                   = Yii::t('app', 'DataBase');
$this->params['breadcrumbs'][] = $this->title;
$this->params['subtitle']      = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));

?>

<div class="box">
    <div class="box-header">
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'db',
        ]), ['create'], ['class' => 'btn btn-success']); ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [
                    'class' => CheckboxColumn::className(),
                ],
                [
                    'class' => ResellerColumn::className(),
                ],
                [
                    'class' => ClientColumn::className(),
                ],
                [
                    'class' => ServerColumn::className(),
                ],
                [
                    'attribute' => 'name'
                ],
                [
                    'attribute' => 'service_ip',

                ],
                [
                    'attribute' => 'name',
                    'label'     => Yii::t('app', 'Username')
                ],
                [
                    'attribute' => 'description'
                ],
                [
                    'class'     => RefColumn::className(),
                    'attribute' => 'state',
                    'format'    => 'raw',
                    'value'     => function ($model) {
                        return State::widget(compact('model'));
                    },
                    'gtype'     => 'state,db',
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {truncate} {delete}',
                    'buttons'  => [
                        'view' => function ($url, $model, $key) {
                            return GridActionButton::widget([
                                'url'   => $url,
                                'icon'  => '<i class="fa fa-eye"></i>',
                                'label' => Yii::t('app', 'Details'),
                            ]);
                        },
                    ],
                ],
            ],
        ]) ?>
    </div>
</div>
<?php Pjax::end(); ?>
