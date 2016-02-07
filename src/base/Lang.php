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

use Yii;

/**
 * AHnames Lang to Yii2 i18n conversion.
 */
class Lang
{
    /**
     * AHNAMES Lang to Yii2 i18n.
     * @param null $txt
     * @param string $path
     *
     * @return string
     */
    public static function t($str = null, $path = 'app')
    {
        $res = self::lang($str, $path);
        return $res === $str ? Yii::t($str, $path) : $res;
    }

    public static function lang($txt = null, $path = 'app')
    {
        return preg_replace_callback('/{(lang):([^}]*)}/i', ['hipanel\base\Lang', 'translate'], $txt);
    }

    public static function translate($a, $path = 'app')
    {
        $str = $a[2];
        $res = Yii::t('app', $str);
        if (ctype_upper($str[0]) || ctype_upper($a[1][0])) {
            $res = ucfirst($res);
        }
        return $res;
    }
}
