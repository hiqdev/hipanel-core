<?

use frontend\widgets\GridActionButton;
use Yii;
use frontend\components\Re;
use frontend\modules\ticket\widgets\Label;
use frontend\modules\ticket\widgets\Topic;
use frontend\widgets\GridView;
use frontend\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

//use yii\web\JsExpression;

// frontend\assets\Select2Asset::register($this);

$this->title = Yii::t('app', 'Tickets');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ticket',
]), ['create'], ['class' => 'btn btn-success']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Advanced search'), '#', ['class' => 'btn btn-success search-button']) ?>

<?= $this->render('_search', [
    'model' => $searchModel,
    'topic_data' => $topic_data,
    'priority_data' => $priority_data,
    'state_data' => $state_data,
]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
//    'tableOptions' => ['class' => 'table-condensed'],
    'filterModel' => $searchModel,
    'id' => 'ticket-grid',
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'subject',
            'format' => 'raw',
            'value' => function ($data) {
                return Html::tag('b', Html::a('#' . $data->id . '&nbsp;' . $data->subject, $data->threadUrl)) . Topic::widget(['topic' => $data->topic]);
            }
        ],
        [
            'attribute' => 'create_time',
            'format' => ['date', 'php:d.m.Y H:i'],
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
            'attribute' => 'author_id',
            'value' => function ($data) {
                return Html::a($data->author, ['/client/client/view', 'id' => $data->author_id]);
            },
            'format' => 'html',
            'filterInputOptions' => ['id' => 'author_id'],
            'label' => Yii::t('app', 'Author'),
            'filter' => Select2::widget([
                'attribute' => 'author_id',
                'model' => $searchModel,
                'url' => Url::to(['/client/client/client-all-list'])
            ]),
        ],
        [
            'attribute' => 'recipient_id',
            'format' => 'html',
            'filterInputOptions' => ['id' => 'recipient_id'],
            'label' => Yii::t('app', 'Recipient'),
            'value' => function ($data) {
                return Html::a($data->recipient, ['/client/client/view', 'id' => $data->recipient_id]);

            },
            'filter' => Select2::widget([
                'attribute' => 'recipient_id',
                'model' => $searchModel,
                'url' => Url::to(['/client/client/can-manage-list'])
            ]),
        ],
        [
            'attribute' => 'priority',
            'format' => 'raw',
            'value' => function ($data) {
                return Label::widget([
                    'type' => 'priority',
                    'label' => Re::l($data->priority_label),
                    'value' => $data->priority,
                ]);
            },
            'filter' => Html::activeDropDownList($searchModel, 'priority', \frontend\models\Ref::getList('type,priority'), [
                'class' => 'form-control',
                'prompt' => Yii::t('app', '--'),
            ]),
        ],
        [
            'attribute' => 'state',
            'format' => 'html',
            'value' => function ($data) {
                return Label::widget([
                    'type' => 'state',
                    'label' => Re::l($data->state_label),
                    'value' => $data->state,
                ]);
            },
            'filter' => Html::activeDropDownList($searchModel, 'state', $state_data, [
                'class' => 'form-control',
                'prompt' => Yii::t('app', '--')
            ]),
        ],
        [
            'attribute' => 'responsible_id',
            'format' => 'html',
            'label' => Yii::t('app', 'Responsible'),
            'filterInputOptions' => ['id' => 'responsible_id'],
            'value' => function ($data) {
                return Html::a($data['responsible'], ['/client/client/view', 'id' => $data->responsible_id]);
            },
            'filter' => Select2::widget([
                'attribute' => 'responsible_id',
                'model' => $searchModel,
                'url' => Url::to(['/client/client/client-all-list'])
            ]),
        ],
        [
            'attribute' => 'answer_count',
            'label' => Yii::t('app', 'Answers'),
        ],
        [
            'attribute' => 'spent',
            'label' => Yii::t('app', 'Spent'),
            'value' => function ($data) {
                return $data['spent'] > 0 ? sprintf("%02d:%02d", floor($data['spent'] / 60), ($data['spent'] % 60)) : '00:00';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {state}',
            'header' => Yii::t('app', 'Actions'),
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return GridActionButton::widget([
                        'url' => $url,
                        'icon' => '<i class="fa fa-eye"></i>',
                        'label' => Yii::t('app', 'Details'),
                    ]);
                },
                'state' => function ($url, $model, $key) {
                    if ($model->state == 'opened') {
//                        $title = Yii::t('app', 'Close');
//                        return Html::a('<i class="fa fa-times"></i>&nbsp;&nbsp;'.$title,
//                            ['close', 'id' => $model->id],
//                            ['title' => $title, 'class' => 'btn btn-default btn-xs', 'data-pjax' => 0]
//                        );
                        return GridActionButton::widget([
                            'url' => ['close', 'id' => $model->id],
                            'icon' => '<i class="fa fa-times"></i>',
                            'label' => Yii::t('app', 'Close'),
                        ]);
                    }
                },
            ],
        ],
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
    ],
]); ?>
