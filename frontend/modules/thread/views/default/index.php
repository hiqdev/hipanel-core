<?
use frontend\widgets\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;

//kartik\select2\Select2Asset::register($this);
frontend\assets\Select2Asset::register($this);

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
        // ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'yii\grid\CheckboxColumn',
            // you may configure additional properties here
        ],
        [
            'attribute'=>'create_time',
            'format'=>['date', 'php:d/m/y H:i'],
            'filter' => DatePicker::widget(
                                  [
                                      'name'=>'create_time',
                                      'dateFormat' => 'dd/MM/yyyy',
                                      'options' => [
                                          'class' => 'form-control',
                                      ],
                                  ])
        ],
        [
            'attribute'=>'author_id',
            'value'=> function ($data) { return $data->author; },
            'filterInputOptions' => ['id'=>'author_id'],
            'header'=>'',
        ],
        [
            'attribute'=>'recipient',
            'format'=>'html',
            'value'=>function($data) {
                    return Html::a($data->recipient, ['/client/default/view','id'=>$data->recipient_id]);

                },
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
//            'template'=>'{view} {update} {delete} {remind}',
//            'buttons'=>[
//                'view'=>function ($url, $model, $key) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view','id'=>$model['id']]);
//                    },
//                'remind'=>function ($url, $model, $key) {
//                        return Html::a('<span class="glyphicon glyphicon-bell"></span>','#',['title'=>'Remind']);
//                    },
//            ],
        ],
    ],
]); ?>
<?
// client ajax get
$getAuthorUrl = yii\helpers\Url::to(['client-list']);
$initScript = <<< SCRIPT
function (elem, callback) {
    var id=$(elem).val();
    $.ajax("{$getAuthorUrl}?id=" + id, {
        dataType: "json"
    }).done(function(data) {
        callback(data.results);
    });
}
SCRIPT;
$this->registerJs("$('#author_id').select2({
    placeholder: 'type author name ...',
    minimumInputLength: 3,
    triggerChange: true,
    allowClear: true,
    width:'100%',
    ajax: {
        url: '{$getAuthorUrl}',
        dataType: 'json',
        data: function (term, page) {
            return {
                search: term
            };
        },
        results: function (data, page) {
            return {
                results: data.results
            };
        }
    },
    initSelection: {$initScript}
});", \yii\web\View::POS_READY, 'author');
?>
