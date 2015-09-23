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
    public $colors  = [];

    public $urlCallback;

    public function getColor($type)
    {
        return $this->colors[$type] ?: $type;
    }

    public function getDataCellValue ($model, $key, $index) {
        $value = $model->{$this->attribute};
        $class = $this->getColor('success');
        if ($value<0) {
            $class = $this->getColor('warning');
        };
        if ($value < -($model->{$this->compare} ?: 0)) {
            $class = $this->getColor('danger');
        };
        $url = $this->urlCallback ? call_user_func($this->urlCallback, $model, $key, $index) : null;
        $txt = Yii::$app->formatter->format($value, ['currency', $model->currency]);
        $ops = ['class' => 'text-'.$class];
        return $url ? Html::a($txt, $url, $ops) : Html::tag('span', $txt, $ops);
    }
}
