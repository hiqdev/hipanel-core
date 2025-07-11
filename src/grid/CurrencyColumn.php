<?php

declare(strict_types=1);
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use Closure;
use hipanel\base\Model;
use hipanel\modules\finance\widgets\ColoredBalance;

/**
 * Class CurrencyColumn.
 */
class CurrencyColumn extends DataColumn
{
    public $attribute = 'balance';
    public $format = 'raw';
    public $filter = false;
    public string $nameAttribute = 'balance';

    /**
     * @var bool|string Whether to compare [[attribute]] with another attribute to change the display colors
     *  - boolean false - do not compare
     *  - string - name of attribute to compare with
     */
    public bool $compare = false;
    public array $colors = [];

    public $urlCallback;

    public ?Closure $valueFormatter = null;

    public function getColor($type)
    {
        return $this->colors[$type] ?? $type;
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
        return ColoredBalance::widget([
            'model' => $model,
            'attribute' => $this->attribute,
            'nameAttribute' => $this->nameAttribute,
            'compare' => $this->compare,
            'colors' => $this->colors,
            'urlCallback' => $this->urlCallback,
            'url' => $this->getUrl($model, $key, $index),
            'valueFormatter' => $this->valueFormatter,
        ]);
    }
}
