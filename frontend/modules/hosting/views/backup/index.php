<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\ClientColumn;
use frontend\components\grid\ServerColumn;
use frontend\components\grid\AccountColumn;
use frontend\components\grid\MainColumn;
use frontend\components\grid\CurrentColumn;
use frontend\components\grid\RefColumn;
use frontend\models\Ref;
use yii\helpers\Html;

$this->title                    = Yii::t('app', 'Backup');
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
            'class'                 => ClientColumn::className(),
        ],
        [
            'class'                 => ServerColumn::className(),
        ],
#        [
#            'class'                 => AccountColumn::className(),
#        ],
        [
            'attribute'             => 'object',
        ],
        [
            'attribute'             => 'name',
            'format'                => 'html',
            'value'                 => function($model) {
                return Html::a($model->name, ["/{$model->object}/view", 'id' => $model->object_id]);
            },
        ],
        [
            'class'                 => CurrentColumn::className(),
        ],
        [
            'attribute'             => 'time',
        ],
        [
            'attribute'             => 'size_gb',
        ],
    ],
]) ?>
    </div>
</div>
