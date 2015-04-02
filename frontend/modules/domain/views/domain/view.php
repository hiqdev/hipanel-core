<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\DetailView;
use frontend\modules\domain\grid\DomainGridView;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Pjax;
use yii\helpers\Json;

$this->title                   = Html::encode($model->domain);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
<div class="row" xmlns="http://www.w3.org/1999/html">

<div class="col-md-4">
    <?= DomainGridView::detailView([
        'model'   => $model,
        'columns' => [
            'seller_id','client_id',
            ['attribute' => 'domain'],
            'state',
            'whois_protected','is_secured',
            'nameservers',
            'created_date','expires','autorenewal',
        ],
    ]) ?>
</div>

<div class="col-md-4">
    <div class="box box-success">
        <div class="box-header"><?= \Yii::t('app', 'Contacts') ?></div>
        <div class="box-body">
        </div>
    </div>
</div>

</div>
<?php Pjax::end();
