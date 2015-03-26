<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\ClientColumn;
use frontend\components\grid\ServerColumn;
use frontend\components\widgets\GridActionButton;
use frontend\components\widgets\Pjax;
use yii\helpers\Html;

$this->title                    = Yii::t('app', 'DataBase');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

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
            'class'                 => CheckboxColumn::className(),
        ],
        [
            'class'                 => ClientColumn::className(),
        ],
        [
            'class'                 => ServerColumn::className(),
        ],
        [
            'class'                 => 'yii\grid\ActionColumn',
            'template'              => '{view} {update} {truncate} {delete}',
            'buttons'                   =>           [
                'view'                      => function ($url, $model, $key) {
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
