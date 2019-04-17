<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function make_sub($array, $v_key, $k_key = null)
    {
        $res = [];
        foreach ($array as $k => $v) {
            $res[$k][$v_key] = $v;
        }
        if ($k_key) {
            foreach ($array as $k => $v) {
                $res[$k][$k_key] = $k;
            }
        }

        return $res;
    }

    /**
     * Parses data, exploding the string by comma, trying to create array.
     *
     * @param $string
     * @param string $delimiter
     * @return array
     */
    public static function csplit($string, $delimiter = ',')
    {
        if (is_array($string)) {
            return $string;
        }
        $res = [];
        foreach (explode($delimiter, $string) as $k => $v) {
            $v = trim($v);
            if (strlen($v)) {
                array_push($res, $v);
            }
        }

        return $res;
    }

    public static function ksplit($string, $delimiter = ',')
    {
        if (is_array($string)) {
            return $string;
        }
        $res = [];
        foreach (explode($delimiter, $string) as $k => $v) {
            $v = trim($v);
            if (strlen($v)) {
                $res[$v] = $v;
            }
        }

        return $res;
    }

    /**
     * Retrieves the values of an array elements or object properties with the given key or property name.
     * Uses [[getValue]] method.
     *
     * @param array|object $array array or object to extract value from
     * @param array [string] $keys keys names of the array elements, or properties of the object
     * @return array
     * @see getValue()
     */
    public static function getValues($array, $keys = [])
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = static::getValue($array, $key);
        }

        return $result;
    }
}
