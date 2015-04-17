<?php

namespace frontend\modules\client\grid;

use frontend\components\grid\DataColumn;
use frontend\components\widgets\Combo2;
use yii\helpers\Html;


class ClientColumn extends DataColumn
{
    public $attribute = 'client_id';

    public $nameAttribute = 'client';

    public $format = 'html';

    public $clientType;

    public function init () {
        parent::init();
        if (is_null($this->visible)) {
            $this->visible = \Yii::$app->user->identity->type != 'client';
        };
        if (!empty($this->grid->filterModel)) {
            if (!$this->filterInputOptions['id']) {
                $this->filterInputOptions['id'] = $this->attribute;
            }
            if (!$this->filter) {
                $this->filter = Combo2::widget([
                    'type'                => 'client',
                    'attribute'           => $this->attribute,
                    'model'               => $this->grid->filterModel,
                    'formElementSelector' => 'td',
                    'options'             => [
                        'clientType' => $this->clientType
                    ]
                ]);
            };
        };
    }

    public function getDataCellValue ($model, $key, $index) {
        return Html::a($model->{$this->nameAttribute}, ['/client/client/view', 'id' => $model->{$this->attribute}]);
    }
}
