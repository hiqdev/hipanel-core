<?
use frontend\widgets\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'yii\grid\CheckboxColumn',
            // you may configure additional properties here
        ],
        'name',
        'email',
        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{view}',
            'buttons'=>[
                'view'=>function ($url, $model, $key) {
                        return Html::a('view',['view','id'=>$model['id']]);
                        // return yii\grid\ActionColumn::createUrl('view',$model,$model['id'],$key);
                    },
            ],
        ],
    ],
]); ?>