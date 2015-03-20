<?php

use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\ClientColumn;
use frontend\components\grid\EditableColumn;
use frontend\components\grid\ResellerColumn;
use frontend\components\widgets\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

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
            'class'                 => CheckboxColumn::className(),
        ],
        [
            'class'                 => ResellerColumn::className(),
        ],
        [
            'class'                 => ClientColumn::className(),
        ],
        [
            'attribute'             => 'host',
            'filterAttribute'       => 'host_like',
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->host, ['view', 'id' => $model->id], ['class' => 'bold']);
            },
        ],
        [
            'class'                 => EditableColumn::className(),
            'attribute'             => 'ips',
            'popover'               => 'Up to 13 IP addresses',
            'filterAttribute'       => 'ips_like',
            'action'                => ['update'],
        ],
    ],
]) ?>
</div>
</div>
