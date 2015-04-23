<?php

namespace frontend\modules\server\grid;

use hipanel\grid\DataColumn;
use hipanel\widgets\Combo2;
use yii\helpers\Html;

class ServerColumn extends DataColumn
{
    public $attribute = 'device_id';
    public $nameAttribute = 'device';
    public $format = 'html';

    public function init () {
        parent::init();
        if (!$this->filterInputOptions['id']) {
            $this->filterInputOptions['id'] = $this->attribute;
        }
        if (!$this->filter) {
            $this->filter = Combo2::widget([
                'type'                => 'server',
                'attribute'           => $this->attribute,
                'model'               => $this->grid->filterModel,
                'formElementSelector' => 'td',
            ]);
        };
    }

    public function getDataCellValue ($model, $key, $index) {
        return Html::a($model->{$this->nameAttribute}, ['/server/server/view', 'id' => $model->{$this->attribute}]);
    }
}
