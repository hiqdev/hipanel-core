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

use NumberFormatter;
use Yii;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * Explodes string into array, optionally trims values and skips empty ones.
     *
     * @param string $string string to be exploded
     * @param string $delimiter Delimiter. should be regular expression
     * @param mixed $trim Whether to trim each element. Can be:
     *   - boolean - to trim normally;
     *   - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *   - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @param boolean $skipEmpty Whether to skip empty strings between delimiters. Default is false.
     * @return array
     * @since 2.0.4
     */
    public static function mexplode($string, $delimiter = '/[\s,;]+/', $trim = true, $skipEmpty = false)
    {
        $result = preg_split($delimiter, $string);
        if ($trim) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!is_callable($trim)) {
                $trim = function ($v) use ($trim) {
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

    /**
     * Returns the symbol used for a currency.
     *
     * @param string $currency      A currency code (e.g. "EUR").
     * @param string|null $locale   optional
     *
     * @return string|null the currency symbol or NULL if not found
     * @see https://stackoverflow.com/questions/13897516/get-currency-symbol-in-php
     */
    public static function getCurrencySymbol($currency, $locale = null)
    {
        if (null === $locale) {
            $locale = Yii::$app->formatter->locale;
        }
        $fmt = new NumberFormatter($locale . "@currency=$currency", NumberFormatter::CURRENCY);
        $symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        return $symbol;
    }
}
