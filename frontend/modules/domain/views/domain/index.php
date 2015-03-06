<?php

use frontend\widgets\GridView;
use frontend\modules\object\widgets\RequestState;
use frontend\widgets\Select2;
use frontend\components\Re;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;

$this->title                    = Yii::t('app', 'Domains');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'full list' : 'filtered list';

?>

<div class="box box-primary">
<div class="box-body">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class' => 'frontend\widgets\CheckboxColumn',
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
            'format'    => 'raw',
            'value'     => function ($model) {
                return SwitchInput::widget([
                    'attribute'     => 'whois_protected',
                    'name'          => 'wpc'.$model->id,
                    'pluginOptions' => [
                        'size'              => 'mini',
                        'state'             => false,
                        'onSwitchChange'    => new JsExpression('function () { console.log("hello"); }'),
                    ],
                ]);
            },
        ],
        [
            #'class'             => 'frontend\widgets\EditableColumn',
            'attribute'         => 'is_secured',
            'label'             => Yii::t('app', 'Locked'),
            'popover'           => Yii::t('app', 'Protection from transfer'),
#           'editableOptions'   => function ($model, $key, $index, $widget) {
#               return [
#                   'inputType'     => Editable::INPUT_SWITCH,
#               ];
#           },
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
