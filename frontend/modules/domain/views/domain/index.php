<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\modules\client\grid\ClientColumn;
use frontend\components\grid\EditableColumn;
use frontend\modules\client\grid\ResellerColumn;
use frontend\components\grid\SwitchColumn;
use frontend\components\grid\MainColumn;
use frontend\components\grid\RefColumn;
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
            'class'                 => MainColumn::className(),
            'attribute'             => 'domain',
        ],
        [
            'class'                 => RefColumn::className(),
            'attribute'             => 'state',
            'format'                => 'raw',
            'value'                 => function ($model) {
                return State::widget(compact('model'));
            },
            'gtype'                 => 'state,domain',
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'whois_protected',
            'popover'               => 'WHOIS protection',
            'pluginOptions'         => [
                'onColor'   => 'success',
                'offColor'  => 'warning',
            ],
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'is_secured',
            'popover'               => Yii::t('app', 'Protection from transfer'),
        ],
        [
            'class'                 => EditableColumn::className(),
            'attribute'             => 'note',
            'filter'                => true,
            'popover'               => Yii::t('app','Make any notes for your convenience'),
            'action'                => ['set-note'],
        ],
        [
            'attribute'             => 'created_date',
            'format'                => 'date',
            'filter'                => false,
            'contentOptions'        => ['class' => 'text-nowrap'],
        ],
        [
            'attribute'             => 'expires',
            'headerOptions'         => ['style' => 'width:1em'],
            'format'                => 'raw',
            'value'                 => function ($model) {
                return Expires::widget(compact('model'));
            },
            'filter'                => false,
        ],
        [
            'class'                 => SwitchColumn::className(),
            'attribute'             => 'autorenewal',
            'label'                 => 'Autorenew',
            'popover'               => 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
            'pluginOptions'         => [
                'onColor'   => 'info',
            ],
        ],
    ],
]) ?>
</div>
</div>
