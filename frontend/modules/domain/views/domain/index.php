<?php

use frontend\components\widgets\GridView;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Select2;
use frontend\components\Re;
use yii\helpers\Url;
use \yii\jui\DatePicker;
use \yii\helpers\Html;

$this->title                    = Yii::t('app', 'Domains');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'full list' : 'filtered list';

?>

<div class="box box-primary">
<div class="box-data">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class' => 'frontend\components\grid\CheckboxColumn',
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
            'attribute' => 'domain',
            'label'     => Yii::t('app', 'Name'),
            'format'    => 'html',
            'value'     => function ($model) {
                return Html::a($model->domain, ['view', 'id' => $model->id]);
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
            'filter'    => Html::activeDropDownList($searchModel, 'state', \frontend\models\Ref::getList('state,domain'), [
                'class'  => 'form-control',
                'prompt' => Yii::t('app', '--'),
            ]),
            'value'     => function ($model) {
                return Re::l($model->state_label);
            },
        ],
        [
            'attribute' => 'note',
            'value'     => function ($model) {
                return $model->note ?: ' ';
            },
        ],
        [
            'attribute' => 'created_date',
            'format'    => ['date'],
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
    ],
]) ?>
</div>
</div>
