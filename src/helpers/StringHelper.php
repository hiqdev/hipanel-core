<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * Explodes string into array, optionally trims values and skips empty ones
     *
     * @param string $string String to be exploded.
     * @param string $delimiter Delimiter. should be regular expression
     * @param mixed $trim Whether to trim each element. Can be:
     *   - boolean - to trim normally;
     *   - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *   - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @param boolean $skipEmpty Whether to skip empty strings between delimiters. Default is false.
     * @return array
     * @since 2.0.4
     */
    public static function mexplode($string, $delimiter = '/[\s,;]+/', $trim = true, $skipEmpty = false) {
        $result = preg_split($delimiter, $string);
        if ($trim) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!is_callable($trim)) {
                $trim = function($v) use ($trim) {
                    return trim($v, $trim);
                };
            }
            $result = array_map($trim, $result);
        }
        if ($skipEmpty) {
            // Wrapped with array_values to make array keys sequential after empty values removing
            $result = array_values(array_filter($result));
        }
        return $result;
    }
}
