<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $data['name'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo DetailView::widget([
    'model' => $data,
    'attributes' => [
        [
            'label' => Yii::t('app','Name'),
            'value' => $data['name'],
        ],
    ],
]);