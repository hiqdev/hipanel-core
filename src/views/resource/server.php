<?php

use hipanel\widgets\ResourceDetailViewer;
use hiqdev\hiart\ActiveDataProvider;
use yii\db\ActiveRecordInterface;

/** @var ActiveRecordInterface $originalModel */
/** @var ActiveDataProvider $dataProvider */

$this->title = $originalModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['@server/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Server traffic'), 'url' => ['/resource/servers']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= ResourceDetailViewer::widget(['dataProvider' => $dataProvider, 'originalContext' => $this->context, 'searchModel' => $model, 'uiModel' => $uiModel, 'originalModel' => $originalModel]) ?>
