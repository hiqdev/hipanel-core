<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 12/3/14
 * Time: 5:17 PM
 */

namespace frontend\components;

use Yii;

class Re {

    /**
     * AHNAMES Lng to Yii2 i18n
     * @param null $txt
     * @param string $path
     *
     * @return string
     */
    public static function l($txt=null, $path='app') {
        if ($txt!==null) {
            return $t=Yii::t($path, str_ireplace(['{lang:','}'], '', $txt));
        }
    }

    static public function isError ($data, $eq = null) {
        return is_null($eq) ? (is_array($data) ? array_key_exists('_error', $data) : !$data) : (isset($data['_error']) && $data['_error'] == $eq);
    }

    static  public function getError ($data,$df=null) { return isset($data['_error']) ? $data['_error'] : $df; }

}