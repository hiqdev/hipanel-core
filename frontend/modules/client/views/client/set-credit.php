<?php
use yii\bootstrap\ButtonGroup;
use frontend\components\widgets\GridView;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$this->title = 'Set credit';
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginForm( [ 'set-credit' ], "POST" );

if (!Yii::$app->request->isAjax) echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']);
if (!Yii::$app->request->isAjax) echo Html::submitButton(Yii::t('app', 'Cancel'), ['type' => 'cancel', 'class' => 'btn btn-success', 'onClick' => "history.back()"]);

Pjax::begin();

$widgetIndexConfig = [
    'dataProvider'  => $dataProvider,
    'columns'       => [
        [
            'label'     => Yii::t('app', 'Client'),
            'format'    => 'raw',
            'value'     => function ($data) {
                return HTML::input("hidden", "ids[{$data->id}][Client][id]", $data->id, ['readonly' => 'readonly', 'disabled' => $data->id == \Yii::$app->user->identity->id || \Yii::$app->user->identity->type == 'client' || \Yii::$app->user->identity->type == 'admin' ]) .  HTML::tag('span', $data->login);
            }
        ],
        [
            'label'     => Yii::t('app','Credit'),
            'format'    => 'raw',
            'value'     => function ($data) {
                return HTML::input('number', "ids[{$data->id}][Client][credit]", $data->credit, [ 'min' => 0, 'step' => 0.01, 'disabled' => $data->id == \Yii::$app->user->identity->id || \Yii::$app->user->identity->type == 'client' || \Yii::$app->user->identity->type == 'admin' ]);
            }
        ],
    ],
];
echo GridView::widget($widgetIndexConfig);

Pjax::end();

echo Html::endForm();


