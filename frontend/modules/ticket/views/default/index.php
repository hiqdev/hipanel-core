<?
use frontend\widgets\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
$this->title = Yii::t('app', 'Tickets');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Ticket',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'yii\grid\CheckboxColumn',
            // you may configure additional properties here
        ],
        [
            'attribute'=>'create_time',
            'format'=>['date'],
            'filter' => DatePicker::widget(
                                  [
                                      'name'=>'create_time',
                                      'options' => [
                                          'class' => 'form-control'
                                      ],
                                      'clientOptions' => [
                                          'dateFormat' => 'dd.mm.yy',
                                      ]
                                  ])
        ],
        [
            'attribute'=>'subject',
            'format'=>'html',
            'value'=>function ($data) {
                    $html = '';
                    $html .= $data['subject'];
                    $html .= '<ul class="list-inline">';
                    foreach ($data['topic'] as $item=>$label) {
                        $html .= '<li><span class="label label-success">'.$item.'</span></li>';
                    }
                    $html .= '</ul>';
                    return $html;
                }
        ],
        [
            'attribute'=>'priority',
            'format'=>'html',
            'value'=>function ($data) {
                    return  '<span class="label label-warning">'.$data['priority'].'</span>';
                }
        ],
        [
            'attribute'=>'state',
            'format'=>'html',
            'value'=>function ($data) {
                    return  '<span class="label label-info">'.$data['priority'].'</span>';
                }
        ],
        [
            'attribute'=>'responsible',
            'format'=>'html',
            'value'=>function ($data) {
                    return Html::a($data['responsible'], ['/client/default/view','id'=>$data['responsible_id']]);
                }
        ],
        'answer_count',
        [
            'attribute'=>'spent',
            'value'=>function ($data) {
                    return $data['spent']>0 ? sprintf("%02d:%02d",floor($data['spent']/60),($data['spent']%60)) : '00:00';
                }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{view} {update} {delete} {remind}',
            'buttons'=>[
                'view'=>function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view','id'=>$model['id']]);
                    },
                'remind'=>function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-bell"></span>','#',['title'=>'Remind']);
                    },
            ],
        ],
    ],
]); ?>