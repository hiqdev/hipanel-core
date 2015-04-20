<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use yii\helpers\Html;

class MainColumn extends DataColumn
{
    protected function renderDataCellContent ($model, $key, $index) {
/* TODO delete if ok
        if ($this->content === null) {
            $value = $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        } else {
            $value = parent::renderDataCellContent($model, $key, $index);
        }
*/
        $value = parent::renderDataCellContent($model, $key, $index);
        return Html::a($value, ['view', 'id' => $model->id], ['class' => 'bold']);
    }
}
