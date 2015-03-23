<?php

use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\ClientColumn;
use frontend\components\grid\EditableColumn;
use frontend\components\grid\ResellerColumn;
use frontend\components\grid\SwitchColumn;
use frontend\components\widgets\GridView;
use frontend\modules\domain\widgets\Expires;
use frontend\modules\domain\widgets\State;
use frontend\models\Ref;
use yii\helpers\Html;

$this->title                    = Yii::t('app', 'Domains');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

?>

<div class="box box-primary">
<div class="box-body">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class'                 => CheckboxColumn::className(),
        ],
        [
            'class'                 => ResellerColumn::className(),
        ],
        [
            'class'                 => ClientColumn::className(),
        ],
        [
            'attribute'             => 'domain',
            'label'                 => Yii::t('app', 'Name'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->domain, ['view', 'id' => $model->id], ['class' => 'bold']);
            },
        ],
        [
            'attribute'             => 'state',
            'format'                => 'raw',
            'value'                 => function ($model) {
                return State::widget(compact('model'));
            },
            'filter'                => Html::activeDropDownList($searchModel, 'state', Ref::getList('state,domain'), [
                'class'     => 'form-control',
                'prompt'    => Yii::t('app', '---'),
            ]),
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'whois_protected',
            'popover'               => 'WHOIS protection',
            'headerOptions'         => ['style' => 'width:1em'],
            'pluginOptions'         => [
                'size'      => 'mini',
                'onColor'   => 'success',
                'offColor'  => 'warning',
            ],
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'is_secured',
            'popover'               => Yii::t('app', 'Protection from transfer'),
            'headerOptions'         => ['style' => 'width:1em'],
            'pluginOptions'         => [
                'size'      => 'mini',
            ],
        ],
        [
            'class'                 => EditableColumn::className(),
            'attribute'             => 'note',
            'popover'               => Yii::t('app','Make any notes for your convenience'),
            'action'                => ['set-note'],
        ],
        [
            'attribute'             => 'created_date',
            'format'                => 'date',
        ],
        [
            'attribute'             => 'expires',
            'headerOptions'         => ['style' => 'width:1em'],
            'format'                => 'raw',
            'value'                 => function ($model) {
                return Expires::widget(compact('model'));
            },
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'autorenewal',
            'label'                 => 'Autorenew',
            'popover'               => 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
            'headerOptions'         => ['style' => 'width:1em'],
            'pluginOptions'         => [
                'size'      => 'mini',
                'onColor'   => 'info',
            ],
        ],
    ],
]) ?>
</div>
</div>
