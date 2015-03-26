<?php

namespace frontend\components\grid;

use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

use yii\web\JsExpression;

class AccountColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'attribute'             => 'account_id',
            'label'                 => \Yii::t('app', 'Account'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->account, ['/hosting/account/view', 'id' => $model->account_id]);
            },
            'filterInputOptions'    => ['id' => 'account_id'],
            'filter'                => Select2::widget([
                'attribute' => 'account_id',
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute(['/hosting/account/list']),
                'settings'  => [
                    'ajax'      => [
                        'data'      => new JsExpression('function(term,page) { return {"rename[text]":"login",wrapper:"results", client_like:term}; }'),
                    ],
                    'initSelection'      => new JsExpression('function (elem, callback) {
                        var id=$(elem).val();
                        $.ajax("' . Url::toRoute(['/hosting/account/list']) . '?id=" + id, {
                            dataType: "json",
                            data : {"rename[text]":"login",wrapper:"results" }
                        }).done(function(data) {
                            callback(data.results[0]);
                        });
                    }'),
                ],
            ]),
        ]);
    }
}
