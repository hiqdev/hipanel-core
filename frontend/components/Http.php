<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 11/24/14
 * Time: 4:41 PM
 */

namespace frontend\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/// Обвёртка вокруг curl'а
/// Особенность: принимают уже готовые данные или сами готовят с помощью http_build_query, сам curl был замечен в том что посылает как-то не так

class Http {
//    static public function fetchGet ($url,$data=null) {
//        $data = is_array($data) ? http_build_query($data, '', '&') : $data;
//        \yii::warning($data);
//        $ch = curl_init($url.($data ? '?'.$data : ''));
//        curl_setopt_array($ch,array(
//            CURLOPT_USERAGENT       => 'curl/0.00 (php 5.x; U; en)',
//            CURLOPT_RETURNTRANSFER  => 1,
//            CURLOPT_SSL_VERIFYPEER  => FALSE,
//            CURLOPT_SSL_VERIFYHOST  => 2,
//            CURLOPT_SSLVERSION      => 3,
//        ));
//        return curl_exec($ch);
//    }

    static public function fetchPost ($url,$data=array()) {
        $data = is_array($data) ? http_build_query($data, '', '&') : $data;
        $ch = curl_init($url);
        curl_setopt_array($ch,array(
            CURLOPT_USERAGENT       => 'curl/0.00 (php 5.x; U; en)',
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_SSL_VERIFYPEER  => FALSE,
            CURLOPT_SSL_VERIFYHOST  => 2,
            CURLOPT_SSLVERSION      => 3,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $data,
        ));
        return curl_exec($ch);
    }

    static public function get($action,$data=array()) {
        $authData = [
            'auth_ip'=>'192.168.1.39',
            'auth_login'=>'tofid',
            'auth_password'=>'1309847555',
        ];
        $fetchData = self::fetchPost('https://api.ahnames.com/'.$action.'/',ArrayHelper::merge($data, $authData));
        return Json::decode($fetchData);
    }
};