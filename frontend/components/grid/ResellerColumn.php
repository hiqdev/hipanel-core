<?php

namespace frontend\components\grid;

use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

class ResellerColumn extends ClientColumn
{
    public $attribute = 'seller_id';

    public $listAction = 'seller-list';

/*
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'visible'               => \Yii::$app->user->identity->type!='client',
            'attribute'             => 'seller_id',
            'label'                 => \Yii::t('app', 'Reseller'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->seller, ['/client/client/view', 'id' => $model->seller_id]);
            },
            'filterInputOptions'    => ['id' => 'seller_id'],
            'filter'                => Select2::widget([
                'attribute' => 'seller_id',
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute(['/client/client/seller-list']),
            ]),
        ]);
    }
*/
}
