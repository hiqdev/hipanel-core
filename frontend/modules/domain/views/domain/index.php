<?php

use frontend\modules\domain\grid\DomainGridView;

$this->title                    = Yii::t('app', 'Domains');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'filtered list' : 'full list';
?>

<?= DomainGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'checkbox',
        'seller_id','client_id',
        'domain','state',
        'whois_protected','is_secured',
        'note',
        'created_date','expires','autorenewal',
    ],
]) ?>
