<?php

use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\GridView;
use frontend\components\widgets\Pjax;
use frontend\components\widgets\Select2;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\GridActionButton;
use frontend\modules\client\grid\ResellerColumn;
use frontend\modules\server\widgets\StateFormatter;
use frontend\modules\server\widgets\DiscountFormatter;
use \yii\helpers\Html;
use frontend\modules\server\grid\ServerColumn;
use frontend\modules\client\grid\ClientColumn;

/**
 * @var frontend\modules\server\models\OsimageSearch $osimages
 */

/**
 * @var frontend\components\View $this
 */

$this->title                   = Yii::t('app', 'Servers');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));
?>
    <div class="box">
        <div class="box-body">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns'      => [
                    [
                        'class' => CheckboxColumn::className(),
                    ],
                    [
                        'class' => ResellerColumn::className(),
                    ],
                    [
                        'class'      => ClientColumn::className(),
                        'clientType' => 'ALL',
                    ],
                    [
                        'class'         => ServerColumn::className(),
                        'attribute'     => 'id',
                        'nameAttribute' => 'name'
                    ],
                    [
                        'attribute'      => 'panel',
                        'format'         => 'text',
                        'contentOptions' => ['class' => 'text-uppercase'],
                        'value'          => function ($model) {
                            return $model->panel ?: '';
                        }
                    ],
                    'tariff',
                    'tariff_note',
                    [

                        'attribute' => 'discounts',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return DiscountFormatter::widget([
                                'current' => $model->discounts['fee']['current'],
                                'next'    => $model->discounts['fee']['next'],
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'state',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return RequestState::widget([
                                'model'  => $model,
                                'module' => 'server'
                            ]);
                        },
                        'filter'    => Html::activeDropDownList($searchModel, 'state', $states, [
                            'class'  => 'form-control',
                            'prompt' => Yii::t('app', '--'),
                        ]),
                    ],
                    [
                        'attribute' => 'sale_time',
                        'format'    => ['date'],
                    ],
                    [
                        'attribute' => 'os',
                        'format'    => 'raw',
                        'value'     => function ($model) use ($osimages) {
                            return frontend\modules\server\widgets\OSFormatter::widget([
                                'osimages'  => $osimages,
                                'imageName' => $model->osimage
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'expires',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return StateFormatter::widget([
                                'model' => $model
                            ]);
                        }
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
<?php
Pjax::end();
