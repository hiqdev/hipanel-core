<?php

use frontend\components\widgets\GridView;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Select2;
use frontend\components\Re;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\editable\Editable;

$this->title                    = Yii::t('app', 'Name Servers');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

?>

<div class="box box-primary">
<div class="box-body">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class'                 => 'frontend\components\grid\CheckboxColumn',
            'headerOptions'         => ['style' => 'width:1em'],
        ],
        [
            'class'                 => 'frontend\components\grid\ResellerColumn',
            'headerOptions'         => ['style' => 'width:15em'],
        ],
        [
            'class'                 => 'frontend\components\grid\ClientColumn',
            'headerOptions'         => ['style' => 'width:15em'],
        ],
        [
            'attribute'             => 'host',
            'label'                 => Yii::t('app', 'Name Server'),
            'filter'                => true,
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->host, ['view', 'id' => $model->id], ['class' => 'bold']);
            },
        ],
        [
            'class'                 => 'frontend\components\grid\EditableColumn',
            'attribute'             => 'ips',
            'value'                 => function ($model) {
                return $model->ips;
            },
        ],
    ],
]) ?>
</div>
</div>
