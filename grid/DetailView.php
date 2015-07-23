<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

class DetailView extends \hiqdev\higrid\DetailView
{
    public $gridViewClass;

    /**
     * @inheritdoc
     */
    protected function createDataColumn($text)
    {
        if ($this->gridViewClass && method_exists($this->gridViewClass,'column')) {
            $column = call_user_func([$this->gridViewClass,'column'],$text);
            if (is_array($column)) {
                $column['attribute'] = $column['attribute'] ?: $text;
                return $this->createColumnObject($column);
            };
        };
        return parent::createDataColumn($text);
    }

}
