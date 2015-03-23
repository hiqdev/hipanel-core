<?php

use frontend\components\Re;
use frontend\components\grid\GridView;
use frontend\components\widgets\GridActionButton;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Pjax;
use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var frontend\components\View $this
 */

$this->title                   = Yii::t('app', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));
?>

    <div class="box">
        <div class="box-header">
            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => 'account',
            ]), ['create'], ['class' => 'btn btn-success']); ?>

            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => 'FTP account',
            ]), ['create-ftp'], ['class' => 'btn btn-success']); ?>

        </div>
        <div class="box-body">
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns'      => [
                    [
                        'class' => 'frontend\components\grid\CheckboxColumn',
                    ],
                    [
                        'attribute'          => 'client_id',
                        'label'              => Yii::t('app', 'Client'),
                        'format'             => 'html',
                        'value'              => function ($model) {
                            return Html::a($model->client, ['/client/client/view', 'id' => $model->client_id]);
                        },
                        'filter'             => Select2::widget([
                            'attribute' => 'client_id',
                            'model'     => $searchModel,
                            'url'       => Url::to(['/client/client/client-all-list'])
                        ]),
                        'filterInputOptions' => ['id' => 'client_id'],
                    ],
                    [
                        'attribute' => 'device_like',
                        'label'     => Yii::t('app', 'Server'),
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return Html::a($model->device, ['/server/server/view', 'id' => $model->device_id]);
                        },
                        'filter'    => Select2::widget([
                            'attribute' => 'device_id',
                            'model'     => $searchModel,
                            'url'       => Url::to(['/server/server/list'])
                        ]),
                    ],
                    [
                        'attribute' => 'login_like',
                        'format'    => 'text',
                        'value'     => function ($model) {
                            return $model->login;
                        }
                    ],
                    [
                        'attribute' => 'state',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return RequestState::widget([
                                'model' => $model
                            ]);
                        },
                        'filter'    => Html::activeDropDownList($searchModel, 'state', $states, [
                            'class'  => 'form-control',
                            'prompt' => Yii::t('app', '--'),
                        ]),
                    ],
                    [
                        'attribute' => 'type',
                        'value'     => function ($model) {
                            return Re::l($model->type_label);
                        },
                        'filter'    => Html::activeDropDownList($searchModel, 'type', $types, [
                            'class'  => 'form-control',
                            'prompt' => Yii::t('app', '---')
                        ])
                    ],
                    [
                        'class'    => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons'  => [
                            'view' => function ($url, $model, $key) {
                                return GridActionButton::widget([
                                    'url'   => $url,
                                    'icon'  => '<i class="fa fa-eye"></i>',
                                    'label' => Yii::t('app', 'Details'),
                                ]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
<?php Pjax::end();
