<?php

namespace frontend\components\grid;
use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

class ResellerColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'visible'               => true,
            'attribute'             => 'seller_id',
            'label'                 => \Yii::t('app', 'Reseller'),
            'value'                 => function ($model) {
                return Html::a($model->seller, ['/client/client/view', 'id' => $model->seller_id]);
            },
            'format'                => 'html',
            'filterInputOptions'    => ['id' => 'seller_id'],
            'filter'                => Select2::widget([
                'attribute' => 'seller_id',
                'model'     => $this->grid->filterModel,
                'url'       => Url::to(['/client/client/seller-list']),
            ]),
        ]);
    }
}
