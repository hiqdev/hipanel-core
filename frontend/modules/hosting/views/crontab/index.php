<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\modules\client\grid\ClientColumn;
use frontend\components\grid\ServerColumn;

$this->title                    = Yii::t('app', 'Crontab');
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
        [
            'attribute'             => 'state_label',
        ]
    ],
]) ?>
</div>
</div>
