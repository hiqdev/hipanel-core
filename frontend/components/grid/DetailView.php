<?php

namespace frontend\components\grid;

class DetailView extends \yii\grid\DetailView
{
    public $gridViewClass;

    /** @inheritdoc */
    protected function createDataColumn($text)
    {
        if ($this->gridViewClass && method_exists($this->gridViewClass,'column')) {
            $column = call_user_func([$this->gridViewClass,'column'],$text);
            if (is_array($column)) {
                $column['attribute'] = $column['attribute'] ?: $text;
                return $this->createColumnObject($column);
            };
        };
        return parent::parentCreateDataColumn($text);
    }

}
