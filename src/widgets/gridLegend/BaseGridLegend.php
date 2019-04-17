<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\gridLegend;

use yii\db\ActiveRecordInterface;

abstract class BaseGridLegend
{
    protected $model;

    public function __construct(ActiveRecordInterface $model)
    {
        $this->model = $model;
    }
}
