<?php
use yii\bootstrap\ButtonGroup;
use frontend\widgets\GridView;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$this->title = Yii::t('app', ucfirst($action)) . " " .Yii::t('app', 'block');
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginForm( [ $action . '-block' ], "POST" );

if (!Yii::$app->request->isAjax) echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']);
if (!Yii::$app->request->isAjax) echo Html::submitButton(Yii::t('app', 'Cancel'), ['type' => 'cancel', 'class' => 'btn btn-success', 'onClick' => "history.back()"]);

Pjax::begin();

$blockReason = \frontend\models\Ref::getList('type,block', true);

$widgetIndexConfig = [
    'dataProvider'  => $dataProvider,
    'columns'       => [
        [
            'label'     => Yii::t('app', 'Client'),
            'format'    => 'raw',
            'value'     => function ($data) {
                return HTML::input("hidden", "ids[{$data->id}][id]", $data->id, ['readonly' => 'readonly', 'disabled' => $data->id == \Yii::$app->user->identity->id || \Yii::$app->user->identity->type == 'client']) .  HTML::tag('span', $data->login);
            }
        ],
        [
            'label'     => Yii::t('app','block reason'),
            'format'    => 'raw',
            'value'     => function ($data) {
                return Html::dropDownList("ids[{$data->id}][type]", '', \frontend\models\Ref::getList('type,block', true), [ 'promt' => Yii::t('app', 'Select block reason') ]);
            }
        ],
        [
            'label'     => Yii::t('app','Comment'),
            'format'    => 'raw',
            'value'     => function ($data) {
                return Html::input('text', "ids[{$data->id}][comment]", '', [ 'toggle-title' => Yii::t('app','Write comment') ]);
            },
        ],
    ],
];
echo GridView::widget($widgetIndexConfig);

Pjax::end();

echo Html::endForm();


