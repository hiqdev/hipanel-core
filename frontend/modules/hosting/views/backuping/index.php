<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\ClientColumn;
use frontend\components\grid\AccountColumn;
use frontend\components\grid\ServerColumn;
use frontend\components\grid\SwitchColumn;
use frontend\components\grid\MainColumn;
use yii\helpers\Html;

$this->title                    = Yii::t('app', 'Backuping');
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
            'class'                 => AccountColumn::className(),
        ],
        [
            'attribute'             => 'object',
        ],
        [
            'class'                 => MainColumn::className(),
            'attribute'             => 'name',
        ],
        [
            'attribute'             => 'backup_count',
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'type_label',
        ],
        [
            'attribute'             => 'state_label',
        ],
        [
            'attribute'             => 'backup_last',
        ],
        [
            'attribute'             => 'total_du_gb',
        ],
        [
            'class'                 => 'yii\grid\ActionColumn',
            'template'              => '{enable} {disable} {delete}',
        ],
    ],
]) ?>
</div>
</div>
