<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

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
