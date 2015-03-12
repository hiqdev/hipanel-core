<?php

namespace frontend\components\grid;
use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

class ClientColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'visible'               => true,
            'attribute'             => 'client_id',
            'label'                 => \Yii::t('app', 'Client'),
            'value'                 => function ($model) {
                return Html::a($model->client, ['/client/client/view', 'id' => $model->client_id]);
            },
            'format'                => 'html',
            'filterInputOptions'    => ['id' => 'client_id'],
            'filter'                => Select2::widget([
                'attribute' => 'client_id',
                'model'     => $this->grid->filterModel,
                'url'       => Url::to(['/client/client/client-all-list']),
            ]),
        ]);
    }
}
