<?php

namespace frontend\components\grid;

use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

class ClientColumn extends DataColumn
{
    public $attribute = 'client_id';

    public $format = 'html';

    public $listAction = 'client-all-list';

    public function init () {
        parent::init();
        if (is_null($visible)) {
            $visible = \Yii::$app->user->identity->type!='client';
        };
        if (!$this->filterInputOptions['id']) {
            $this->filterInputOptions['id'] = $this->attribute;
        };
        if (!$this->filter) {
            $this->filter = Select2::widget([
                'attribute' => $this->getFilterAttribute(),
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute(['/client/client/'.$this->listAction]),
            ]);
        };
    }

    public function getDataCellValue ($model, $key, $index) {
        return Html::a($model->client, ['/client/client/view', 'id' => $model->{$this->attribute}]);
    }
}
