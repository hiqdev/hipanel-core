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
        $value = parent::renderDataCellContent($model, $key, $index);
        return Html::a($value, ['view', 'id' => $model->id], ['class' => 'bold']);
    }
}
