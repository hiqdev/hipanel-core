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
}