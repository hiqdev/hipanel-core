<?php
namespace common\components;

use Yii;

class Lang
{
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