<?php
use frontend\widgets\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Tag');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p>
    <?= Html::a(Yii::t('app', 'Create {modelClass}', [
        'modelClass' => 'Tag',
    ]), ['create'], ['class' => 'btn btn-success']) ?>
</p>


<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['class' => 'yii\grid\CheckboxColumn'],
        'client',
        [
            'attribute'=>'name',
        ],
        'descr',
        [
            'label'=>'Preview',
            'format'=>'html',
            'value'=>function($data) {
                    return '<span class="label" style="background-color: '.$data['bgcolor'].';color:'.$data['color'].';">'.$data['name'].'</span>';
                },
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>