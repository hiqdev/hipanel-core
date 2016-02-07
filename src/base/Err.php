<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

class Err
{
    public static function is($data, $eq = null)
    {
        return is_null($eq) ? (is_array($data) ? array_key_exists('_error', $data) : !$data) : (isset($data['_error']) && $data['_error'] === $eq);
    }

    public static function not($data, $eq = null)
    {
        return !static::is($data, $eq);
    }

    public static function get($data, $df = null)
    {
        return isset($data['_error']) ? $data['_error'] : $df;
    }

    public static function set($data, $code, $ops = null)
    {
        if (!is_array($data)) {
            $data = ['_error' => $code];
        } else {
            $data['_error'] = $code;
        }
        if ($ops) {
            $data['_error_ops'] = $ops;
        }
        return $data;
    }

    public static function setifnot($data, $default = 'unknown error')
    {
        if (static::not($data)) {
            return $data;
        }
        if (!static::get($data)) {
            static::set($data, $default);
        }
        return $data;
    }

    public static function reduce($res)
    {
        if (!count($res)) {
            return static::set($res, 'nothing was done');
        }
        $errors = [];
        foreach ($res as $k => $v) {
            if (static::is($v)) {
                $errors[$k] = static::get($v);
            }
        }
        $row_num = count($res);
        $err_num = count($errors);
        if ($err_num) {
            $res['_error'] = $row_num < 2 ? reset($errors) : (($row_num > $err_num) ? 'partially' : 'completely') . " failed ($err_num/$row_num): " . implode(', ', array_unique($errors));
        }
        /* if ($set_nums) {
            $res['_error_num'] = $err_num;
            $res['_total_num'] = $row_num;
        }; */
        return $res;
    }

    public static function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (substr($k, 0, 6) === '_error') {
                    unset($data[$k]);
                }
            }
        }
        return $data;
    }

    public static function isError($data, $eq = null)
    {
        return is_null($eq) ? (is_array($data) ? array_key_exists('_error', $data) : !$data) : (isset($data['_error']) && $data['_error'] === $eq);
    }

    public static function getError($data, $df = null)
    {
        return isset($data['_error']) ? $data['_error'] : $df;
    }
}
