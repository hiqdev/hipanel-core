<?php

use yii\helpers\Html;
use frontend\components\widgets\ActionBox;
use frontend\components\widgets\Pjax;
use frontend\modules\hosting\grid\DbGridView;

$this->title                   = Yii::t('app', 'Databases');
$this->params['breadcrumbs'][] = $this->title;
$this->params['subtitle']      = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])); ?>

<?php $box = ActionBox::begin(['options' => ['class' => 'box-info']]) ?>
<?php $box->beginActions(); ?>
<?= Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'DB']), ['create'], ['class' => 'btn btn-success']) ?>&nbsp;
<?php $box->endActions(); ?>
<?php $box::end(); ?>

<?= DbGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'checkbox',
        'seller_id',
        'client_id',
        'name',
        'service_ip',
        'description',
        'state',
        'actions'
    ],
]) ?>

<?php Pjax::end(); ?>
