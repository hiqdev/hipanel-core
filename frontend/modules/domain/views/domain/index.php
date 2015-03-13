<?php

use frontend\components\widgets\GridView;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Select2;
use frontend\components\Re;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\editable\Editable;

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
            'class'                 => 'frontend\components\grid\CheckboxColumn',
            'headerOptions'         => ['style' => 'width:1em'],
        ],
        [
            'class'                 => 'frontend\components\grid\ResellerColumn',
            'label'                 => 'RRRR',
        ],
        [
            'class'                 => 'frontend\components\grid\ClientColumn',
            'label'                 => 'RRRR',
        ],
        [
            'attribute' => 'domain',
            'label'     => Yii::t('app', 'Name'),
            'format'    => 'html',
            'value'     => function ($model) {
                return Html::a($model->domain, ['view', 'id' => $model->id], ['class' => 'bold']);
            },
        ],
        [
            'attribute'         => 'state',
            'filter'            => Html::activeDropDownList($searchModel, 'state', \frontend\models\Ref::getList('state,domain'), [
                'class'  => 'form-control',
                'prompt' => Yii::t('app', '---'),
            ]),
            'format'            => 'raw',
            'value'             => function ($model) {
                return \frontend\modules\domain\widgets\State::widget(compact('model'));
            },
        ],
        [
            'class'             => 'frontend\components\grid\SwitchColumn',
            'attribute'         => 'whois_protected',
            'popover'           => 'WHOIS protection',
            'headerOptions'     => ['style' => 'width:1em'],
            'pluginOptions'     => [
                'size'              => 'mini',
                'onColor'           => 'success',
                'offColor'          => 'warning',
            ],
        ],
        [
            'class'             => 'frontend\components\grid\SwitchColumn',
            'attribute'         => 'is_secured',
            'popover'           => Yii::t('app', 'Protection from transfer'),
            'headerOptions'     => ['style' => 'width:1em'],
            'pluginOptions'     => [
                'size'              => 'mini',
            ],
        ],
        [
            'class'             => 'frontend\components\grid\EditableColumn',
            'attribute'         => 'note',
            'popover'           => Yii::t('app','Make any notes for your convenience'),
            'editableOptions'   => function ($model) {
                return [
                    'inputType'     => Editable::INPUT_TEXT,
                ];
            },
        ],
        [
            'attribute'         => 'created_date',
            'format'            => 'date',
        ],
        [
            'attribute'         => 'expires',
            'headerOptions'     => ['style' => 'width:1em'],
            'format'            => 'raw',
            'value'             => function ($model) {
                return \frontend\modules\domain\widgets\Expires::widget(compact('model'));
            },
        ],
        [
            'class'             => 'frontend\components\grid\SwitchColumn',
            'attribute'         => 'autorenewal',
            'label'             => 'Autorenew',
            'popover'           => 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
            'headerOptions'     => ['style' => 'width:1em'],
            'pluginOptions'     => [
                'size'              => 'mini',
                'onColor'           => 'info',
            ],
        ],
    ],
]) ?>
</div>
</div>
