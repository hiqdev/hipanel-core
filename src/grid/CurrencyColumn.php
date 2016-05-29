<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\base\Model;
use Yii;
use yii\helpers\Html;

/**
 * Class CurrencyColumn.
 */
class CurrencyColumn extends DataColumn
{
    public $attribute = 'balance';
    public $nameAttribute = 'balance';
    public $format = 'html';
    public $filter = false;

    /**
     * @var bool|string Whether to compare [[attribute]] with another attribute to change the display colors
     *  - boolean false - do not compare
     *  - string - name of attribute to compare with
     */
    public $compare = false;
    public $colors = [];

    public $urlCallback;

    public function getColor($type)
    {
        return $this->colors[$type] ?: $type;
    }

    public function getUrl($model, $key, $index)
    {
        return $this->urlCallback ? call_user_func($this->urlCallback, $model, $key, $index) : null;
    }

    /**
     * @param Model $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = $model->getAttribute($this->attribute);
        $color = $value === 0 ? 'primary' : 'success';

        if ($value < 0) {
            $color = 'warning';
        }

        if ($value < -($model->getAttribute($this->compare) ?: 0)) {
            $color = 'danger';
        }

        $url = $this->getUrl($model, $key, $index);
        $txt = Yii::$app->formatter->format($value, ['currency', $model->currency]);
        $ops = ['class' => 'text-nowrap text-' . $this->getColor($color)];
        return $url ? Html::a($txt, $url, $ops) : Html::tag('span', $txt, $ops);
    }
}
