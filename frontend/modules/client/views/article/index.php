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
        ['class' => 'yii\grid\CheckboxColumn'],
        'article_name',
        'post_date',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>