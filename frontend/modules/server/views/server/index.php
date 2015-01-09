<?php
use frontend\widgets\GridView;
use app\modules\server\widgets\DiscountFormatter;
use app\modules\object\widgets\RequestState;
use \yii\jui\DatePicker;
use \yii\helpers\Html;

/**
 * @var app\modules\server\models\OsimageSearch $osimages
 */

$this->title                   = Yii::t('app', 'Servers');
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        'seller',
        'client',
        'name',
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
            'attribute' => 'state_label',
            'format'    => 'raw',
            'value'     => function ($model) {
                return RequestState::widget([
                    'model' => $model
                ]);
            }
        ],
        [
            'attribute' => 'sale_time',
            'format'    => ['date'],
        ],
        [
            'attribute' => 'os',
            'format'    => 'raw',
            'value'     => function ($model) use ($osimages) {
                return \app\modules\server\widgets\OSFormatter::widget([
                    'osimages'  => $osimages,
                    'imageName' => $model->osimage
                ]);
            }
        ],
        [
            'attribute' => 'expires',
            'format'    => 'raw',
            'value'     => function ($model) {
                if ($model['state'] != 'blocked') {
                    $value = \yii::$app->formatter->asDate($model->expires);
                } else {
                    $value = \yii::t('app', 'Blocked') . ' ' . frontend\components\Re::l($model['block_reason_label']);
                }

                $class = ['label'];

                if (strtotime("+7 days", time()) < strtotime($model->expires)) {
                    $class[] = 'label-info';
                } elseif (strtotime("+3 days", time()) < strtotime($model->expires)) {
                    $class[] = 'label-warning';
                } else {
                    $class[] = 'label-danger';
                }
                $html = Html::tag('span', $value, ['class' => implode(' ', $class)]);
                return $html;
            }
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons'  => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id]);
                },
            ],
        ],
    ],
]);

?>
