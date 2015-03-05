<?php

use frontend\widgets\GridView;
use frontend\modules\object\widgets\RequestState;
use frontend\widgets\Select2;
use yii\helpers\Url;
use \yii\jui\DatePicker;
use \yii\helpers\Html;

$this->title                   = Yii::t('app', 'Domains');
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'visible'               => false,
            'attribute'             => 'seller_id',
            'label'                 => Yii::t('app', 'Reseller'),
            'value'                 => function ($model) {
                return Html::a($model->seller, ['/client/client/view', 'id' => $model->seller_id]);
            },
            'format'                => 'html',
            'filterInputOptions'    => ['id' => 'seller_id'],
            'filter'                => Select2::widget([
                'attribute' => 'seller_id',
                'model'     => $searchModel,
                'url'       => Url::to(['/client/client/seller-list']),
            ]),
        ],
        [
            'visible'               => false,
            'attribute'             => 'client_id',
            'label'                 => Yii::t('app', 'Client'),
            'value'                 => function ($model) {
                return Html::a($model->client, ['/client/client/view', 'id' => $model->client_id]);
            },
            'format'                => 'html',
            'filterInputOptions'    => ['id' => 'author_id'],
            'filter'                => Select2::widget([
                'attribute' => 'client_id',
                'model'     => $searchModel,
                'url'       => Url::to(['/client/client/client-all-list'])
            ]),
        ],
        [
            'attribute' => 'domain_like',
            'label'     => Yii::t('app', 'Name'),
            'value'     => function ($model) {
                return $model->domain;
            },
        ],
        [
            'attribute' => 'whois_protected',
            'label'     => Yii::t('app', 'Whois'),
            'popover'   => 'WHOIS protected',
        ],
        [
            'attribute' => 'is_secured',
            'label'     => Yii::t('app', 'Locked'),
            'popover'   => Yii::t('app', 'Protected from transfer'),
        ],
        [
            'attribute' => 'state',
            'format'    => 'raw',
            'filter'    => Html::activeDropDownList($searchModel, 'state', \frontend\models\Ref::getList('state,domain'), [
                'class'  => 'form-control',
                'prompt' => Yii::t('app', '--'),
            ]),
        ],
        [
            'attribute' => 'registered',
            'format'    => ['date'],
        ],
        [
            'attribute' => 'note',
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
