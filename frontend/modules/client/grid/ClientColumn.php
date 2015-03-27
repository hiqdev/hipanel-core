<?php

namespace frontend\modules\client\grid;

use frontend\components\grid\DataColumn;
use frontend\components\widgets\Combo2;
use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

use yii\web\JsExpression;

class ClientColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this, [
            'visible'            => \Yii::$app->user->identity->type != 'client',
            'attribute'          => 'client_id',
            'label'              => \Yii::t('app', 'Client'),
            'format'             => 'html',
            'value'              => function ($model) {
                return Html::a($model->client, ['/client/client/view', 'id' => $model->client_id]);
            },
            'filterInputOptions' => ['id' => 'client_id'],
            'filter'             => Combo2::widget([
                'type'                => 'client',
                'attribute'           => 'client_id',
                'model'               => $this->grid->filterModel,
                'formElementSelector' => 'td',
            ]),
        ]);
    }
}
