<?php

/**
   * @link    http://hiqdev.com/hipanel
   * @license http://hiqdev.com/hipanel/license
   * @copyright Copyright (c) 2015 HiQDev
   */

namespace hipanel\grid;

use hipanel\grid\DataColumn;
use yii\helpers\Html;
use yii\i18n\Formatter;

class CurrencyColumn extends DataColumn
{
    public $attribute = 'balance';
    public $nameAttribute = 'balance';
    public $format = 'html';
    public $filter = false;
    public $compare = false;
    private $formatter;

    public function init () {
        parent::init();
        $this->formatter = new Formatter;
    }

    public function getDataCellValue ($model, $key, $index) {
        return Html::tag('span', $this->formatter->format($model->{$this->attribute}, [
                'currency',
                $model->currency,
            ]),
            [ 
                'class' => 'label label-' . ($this->compare
                ? 'label label-' . ($model->{$this->attribute} + $model->{$this->compare} < 0 
                    ? 'danger'
                    : ($model->{$this->attribute} < 0 ? 'warning' : 'info')
                )
                : 'label label-' . ($model->{$this->attribute} < 0 ? 'danger' : 'info')
            )]
        );
    }
}
