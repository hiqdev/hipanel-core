<?php

namespace frontend\components\grid;

use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

use yii\web\JsExpression;

class ServerColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'attribute'             => 'server_id',
            'label'                 => \Yii::t('app', 'Client'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->server, ['/server/server/view', 'id' => $model->server_id]);
            },
            'filterInputOptions'    => ['id' => 'server_id'],
            'filter'                => Select2::widget([
                'attribute' => 'client_id',
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute(['/server/server/list']),
                'settings'  => [
                    'ajax'      => [
                        'data'      => new JsExpression('function(term,page) { return {"rename[text]":"name",wrapper:"results", server_like:term}; }'),
                    ],
                    'initSelection'      => new JsExpression('function (elem, callback) {
                        var id=$(elem).val();
                        $.ajax("' . Url::toRoute(['/server/server/list']) . '?id=" + id, {
                            dataType: "json",
                            data : {"rename[text]":"name",wrapper:"results" }
                        }).done(function(data) {
                            callback(data.results[0]);
                        });
                    }'),
                ],
            ]),
        ]);
    }
}
