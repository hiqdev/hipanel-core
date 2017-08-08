<?php

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
