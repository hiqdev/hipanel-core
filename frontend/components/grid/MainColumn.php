<?php

namespace frontend\components\grid;

use yii\helpers\Html;

class MainColumn extends DataColumn
{
    protected function renderDataCellContent ($model, $key, $index) {
        if ($this->content === null) {
            $value = $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        } else {
            $value = parent::renderDataCellContent($model, $key, $index);
        }
        return Html::a($value, ['view', 'id' => $model->id], ['class' => 'bold']);
    }
}
