<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\widgets\Select2;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

class CanManageColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        Yii::configure($this, [
            'visible'               => Yii::$app->user->identity->type !== 'client',
            'attribute'             => 'seller_id',
            'label'                 => Yii::t('hipanel', 'Can Manage'),
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
                    }'),
                ],
            ]),
        ]);
    }
}
