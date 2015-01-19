<?php
namespace common\components;

class Err
{
    static public function is($data, $eq = null) {
        return is_null($eq) ? (is_array($data) ? array_key_exists('_error', $data) : !$data) : (isset($data['_error']) && $data['_error'] == $eq);
    }

    static public function not($data, $eq = null) { return !self::is($data, $eq); }

    static public function get($data, $df = null) { return isset($data['_error']) ? $data['_error'] : $df; }

    static public function set($data, $code, $ops = null) {
        if (!is_array($data)) $data = ['_error' => $code];
        else $data['_error'] = $code;
        if ($ops) $data['_error_ops'] = $ops;
        return $data;
    }

    static public function setifnot($data, $default = 'unknown error') {
        if (err::not($data)) return $data;
        if (!err::get($data)) err::set($data, $default);
        return $data;
    }

    static public function reduce($res) {
        if (!arr::size($res)) return err::set($res, 'nothing was done');
        foreach ($res as $k => $v) if (err::is($v)) $errors[$k] = err::get($v);
        $row_num = arr::size($res);
        $err_num = arr::size($errors);
        if ($err_num) $res['_error'] = $row_num < 2 ? reset($errors) : (($row_num > $err_num) ? 'partially' : 'completely') . " failed ($err_num/$row_num): " . re::cjoin(array_unique($errors));
        /* if ($set_nums) {
            $res['_error_num'] = $err_num;
            $res['_total_num'] = $row_num;
        }; */
        return $res;
    }

    static public function clean($data) {
        if (is_array($data)) foreach ($data as $k => $v) if (substr($k, 0, 6) == '_error') unset($data[$k]);
        return $data;
    }
}