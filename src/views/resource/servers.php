<?php

use hipanel\widgets\ResourceListViewer;
use hiqdev\hiart\ActiveDataProvider;

/** @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('hipanel', 'Server resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['@server/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= ResourceListViewer::widget(['dataProvider' => $dataProvider, 'originalContext' => $this->context, 'searchModel' => $model, 'uiModel' => $uiModel]) ?>
