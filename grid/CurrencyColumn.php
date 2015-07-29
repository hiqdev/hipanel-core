<?php

namespace hipanel\grid;

use Yii;
use yii\helpers\Html;
use yii\i18n\Formatter;

class CurrencyColumn extends DataColumn
{
    public $attribute = 'balance';
    public $nameAttribute = 'balance';
    public $format = 'html';
    public $filter = false;
    public $compare = false;

    public function getDataCellValue ($model, $key, $index) {
        $value = $model->{$this->attribute};
        $class = 'info';
        if ($value<0) {
            $class = 'warning';
        };
        if ($value < -($model->{$this->compare} ?: 0)) {
            $class = 'danger';
        };
        return Html::tag('span',
            Yii::$app->formatter->format($value, ['currency', $model->currency]),
            ['class' => 'text-'.$class]
        );
    }
}
