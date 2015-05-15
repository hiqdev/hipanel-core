<?php

namespace hipanel\widgets\combo;

use hipanel\widgets\Combo;
use yii\helpers\ArrayHelper;

/**
 * Class Default
 *
 * @property mixed data
 */
class StaticCombo extends Combo
{
    public $type = 'default';

    public $_data = [];

    public function getPluginOptions($options = [])
    {
        $options = parent::getPluginOptions();
        unset($options['select2Options']['ajax']);

        return ArrayHelper::merge($options, [
            'select2Options' => [
                'data' => $this->data
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $res  = [];
        $data = (array)$data;

        foreach ($data as $key => $value) {
            if ($value instanceof \Closure) {
                $res[] = call_user_func($value, $this);
            } elseif (!is_array($value)) {
                $res[] = ['id' => $key, 'text' => $value];
            }
        }
        $this->_data = $res;
    }
}

