<?php

namespace frontend\modules\client\grid;

use frontend\components\grid\DataColumn;
use frontend\components\widgets\Combo2;
use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

use yii\web\JsExpression;

class ResellerColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this, [
            'visible'            => \Yii::$app->user->identity->type != 'client',
            'attribute'          => 'seller_id',
            'label'              => \Yii::t('app', 'Reseller'),
            'format'             => 'html',
            'value'              => function ($model) {
                return Html::a($model->seller, ['/client/client/view', 'id' => $model->seller_id]);
            },
            'filterInputOptions' => ['id' => 'seller_id'],
            'filter'             => Combo2::widget([
                'type'                => 'reseller',
                'attribute'           => 'client_id',
                'model'               => $this->grid->filterModel,
                'formElementSelector' => 'td',
            ]),
        ]);
    }
}
