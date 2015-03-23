<?php

namespace frontend\components\grid;

use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

use yii\web\JsExpression;

class CanManageColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'visible'               => \Yii::$app->user->identity->type!='client',
            'attribute'             => 'seller_id',
            'label'                 => \Yii::t('app', 'Can Manage'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->client, ['/client/client/view', 'id' => $model->client_id]);
            },
            'filterInputOptions'    => ['id' => 'client_id'],
            'filter'                => Select2::widget([
                'attribute' => 'seller_id',
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute(['/client/client/list']),
                'settings'  => [
                    'ajax'      => [
                        'data'      => new JsExpression('function(term,page) { return {"rename[text]":"login",wrapper:"results", manager_only:true, client_like:term}; }'),
                    ],
                    'initSelection'      => new JsExpression('function (elem, callback) {
                        $.ajax("' . Url::toRoute(['/client/client/list']) . '?id=" + id, {
                            dataType: "json",
                            data : {"rename[text]":"login",wrapper:"results" }
                        }).done(function(data) {
                            callback(data.results[0]);
                        });
                    }')
                ],
            ]),
        ]);
    }
}
