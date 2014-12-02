<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Html::encode($model->article_name); // $model->article_name
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// \yii\helpers\VarDumper::dump($model, 10, true);
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
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'article_name',
            [
                'attribute'=>'data',
            ],
            [
                'attribute'=>'post_date',
                'format'=>['date','yyyy-mm-dd'],
            ],
        ],
    ]) ?>

</div>