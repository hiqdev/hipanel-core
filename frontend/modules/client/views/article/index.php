<?php
use frontend\widgets\GridView;

use yii\helpers\Html;

$this->title = Yii::t('app', 'News and articles');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p>
    <?= Html::a(Yii::t('app', 'Create {modelClass}', [
        'modelClass' => 'Article',
    ]), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],
        ['class' => 'yii\grid\CheckboxColumn'],
        'id',
        'article_name',
        [
            'attribute'=>'post_date',
            'format'=>['date','yyyy-mm-dd'],
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);?>