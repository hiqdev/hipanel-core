<?php

namespace frontend\components\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    static public function make_sub ($array, $v_key, $k_key = null) {
        $res = [];
        foreach ($array as $k => $v) {
            $res[$k][$v_key] = $v;
        }
        if ($k_key) foreach ($array as $k => $v) {
            $res[$k][$k_key] = $k;
        }

        return $res;
    }
}