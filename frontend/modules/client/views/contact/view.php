<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $data['name'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="event-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        ?>
    </p>
</div>
<h1><?= Yii::t('app', 'Main Info') ?></h1>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'seller',
        'login',
        'type',
        'name',
        'state',
        [
            'label' => Yii::t('app', 'Language'),
            'value' => $model->contact['language'],
        ]
    ],
]) ?>
<h2><?= Yii::t('app', 'Tariff') ?></h2>
<?= DetailView::widget([
    'model'     => $model,
    'attributes'=> [
        [
            'attribute' => 'tariff_name',
            'label'     => Yii::t('app', 'Tariff'),
        ],
    ]
]) ?>
<h2><?= Yii::t('app', 'Balance') ?></h2>
<?= DetailView::widget([
    'model'     => $model,
    'attributes'=> [
        'credit',
        'balance',
    ]
]) ?>
<h2><?= Yii::t('app', 'Contact') ?></h2>

