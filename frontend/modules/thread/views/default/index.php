<?
use frontend\widgets\GridView;
use yii\helpers\Html;
// use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;


// frontend\assets\Select2Asset::register($this);

$this->title = Yii::t('app', 'Tickets');
$this->params['breadcrumbs'][] = $this->title;

$initClientScript = <<< SCRIPT
function (elem, callback) {
    var id=$(elem).val();
    $.ajax("{$getClientUrl}?id=" + id, {
        dataType: "json"
    }).done(function(data) {
        callback(data.results);
    });
}
SCRIPT;
?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>



<div class="row">
    <?=print Html::beginForm('','get');?>
    <div class="col-md-6">
        <p>
            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => 'Ticket',
            ]), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="col-md-4">

        <?
        print '<label class="control-label">'.Yii::t('app','Dates range').'</label>';
        print DatePicker::widget([
            'model'=>$searchModel,
            'attribute' => 'time_from',
            'value' => date('d-m-Y'),
            'type' => DatePicker::TYPE_RANGE,
            'attribute2' => 'time_till',
            'value2' => date('d-m-Y'),
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd-mm-yyyy'
            ]
        ]);
        ?>
    </div>
    <div class="col-md-2"><?=Html::submitButton(Yii::t('app','Sort'));?></div>
    <?=Html::endForm();?>
</div>

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
//            'filter' => DatePicker::widget(
//                                  [
//                                      'name'=>'create_time',
//                                      'dateFormat' => 'dd/MM/yyyy',
//                                      'options' => [
//                                          'class' => 'form-control',
//                                      ],
//                                  ])
        ],
        [
            'attribute'=>'author_id',
            'value'=> function ($data) {
                    return Html::a($data->author, ['/client/default/view','id'=>$data->author_id]);
                },
            'format'=>'html',
            'filterInputOptions' => ['id'=>'author_id'],
            'label'=>Yii::t('app','Author'),
            'filter'=> frontend\widgets\Select2::widget([
                'attribute'=>'author_id',
                'model'=>$searchModel,
                'options'=>[
                    'id'=>'author_id',
                ],
                'settings' => [
                    'allowClear' => true,
                    'placeholder'=>'Type name ...',
                    'width'=>'100%',
                    'triggerChange' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => yii\helpers\Url::to(['client-list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                                                            var id=$(elem).val();
                                                            $.ajax("'.yii\helpers\Url::to(['client-list']).'?id=" + id, {
                                                                dataType: "json"
                                                            }).done(function(data) {
                                                                callback(data.results);
                                                            });
                                                        }')
                ],
            ]),
        ],
        [
            'attribute'=>'recipient_id',
            'format'=>'html',
            'filterInputOptions' => ['id'=>'recipient_id'],
            'label'=>Yii::t('app','Recipient'),
            'value'=>function($data) {
                    return Html::a($data->recipient, ['/client/default/view','id'=>$data->recipient_id]);

            },
            'filter'=> frontend\widgets\Select2::widget([
                    'attribute'=>'recipient_id',
                    'model'=>$searchModel,
                    'options'=>[
                        'id'=>'recipient_id',
                    ],
                    'settings' => [
                        'allowClear' => true,
                        'placeholder'=>'Type name ...',
                        'width'=>'100%',
                        'triggerChange' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => yii\helpers\Url::to(['manager-list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                        ],
                        'initSelection' => new JsExpression('function (elem, callback) {
                                                            var id=$(elem).val();
                                                            $.ajax("'.yii\helpers\Url::to(['manager-list']).'?id=" + id, {
                                                                dataType: "json"
                                                            }).done(function(data) {
                                                                callback(data.results);
                                                            });
                                                        }')
                    ],
                ]),
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
                    return  '<span class="label label-warning">'.$data->priority.'</span>';
                },
            'filter'=> Html::activeDropDownList($searchModel,
                    'priority',
                    frontend\models\Ref::getList('priority'),
                    [
                        'class'  => 'form-control',
                        'prompt' => Yii::t('app', '--'),
                    ]),
        ],
        [
            'attribute'=>'state',
            'format'=>'html',
            'value'=>function ($data) {
                    return  '<span class="label label-info">'.$data->state.'</span>';
                },
            'filter'=> Html::activeDropDownList($searchModel,
                    'state',
                    ArrayHelper::map(frontend\models\Ref::find()->where(['gtype'=>'state,ticket'])->getList(),'gl_key',
                        function($v){
                            return frontend\components\Re::l($v->gl_value);
                        }),
                    [
                        'class'  => 'form-control',
                        'prompt' => Yii::t('app', '--'),
                    ]),
        ],
        [
            'attribute'=>'responsible_id',
            'format'=>'html',
            'label'=>Yii::t('app','Responsible'),
            'filterInputOptions' => ['id'=>'responsible_id'],
            'value'=>function ($data) {
                    return Html::a($data['responsible'], ['/client/default/view','id'=>$data->responsible_id]);
                },
            'filter'=> frontend\widgets\Select2::widget([
                    'attribute'=>'responsible_id',
                    'model'=>$searchModel,
                    'options'=>[
                        'id'=>'responsible_id',
                    ],
                    'settings' => [
                        'allowClear' => true,
                        'placeholder'=>'Type name ...',
                        'width'=>'100%',
                        'triggerChange' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => yii\helpers\Url::to(['client-list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                        ],
                        'initSelection' => new JsExpression('function (elem, callback) {
                                                            var id=$(elem).val();
                                                            $.ajax("'.yii\helpers\Url::to(['client-list']).'?id=" + id, {
                                                                dataType: "json"
                                                            }).done(function(data) {
                                                                callback(data.results);
                                                            });
                                                        }')
                    ],
                ]),
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
