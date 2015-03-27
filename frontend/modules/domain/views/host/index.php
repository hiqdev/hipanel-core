<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\modules\client\grid\ClientColumn;
use frontend\modules\client\grid\ResellerColumn;
use frontend\components\grid\EditableColumn;
use frontend\components\grid\MainColumn;

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
            'class'                 => MainColumn::className(),
            'attribute'             => 'host',
            'filterAttribute'       => 'host_like',
        ],
        [
            'class'                 => EditableColumn::className(),
            'attribute'             => 'ips',
            'filterAttribute'       => 'ips_like',
            'popover'               => 'Up to 13 IP addresses',
            'action'                => ['update'],
        ],
    ],
]) ?>
</div>
</div>
