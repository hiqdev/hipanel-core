<?php

namespace hipanel\components;

use NumberFormatter;

class Formatter extends \yii\i18n\Formatter
{
    private array $_numberFormatterCache = [];

    protected function createNumberFormatter($style, $decimals = null, $options = [], $textOptions = [])
    {
        $cacheKey = $style . '_' . $decimals . '_' . serialize($options) . '_' . serialize($textOptions);
        if (!isset($this->_numberFormatterCache[$cacheKey])) {
            $this->_numberFormatterCache[$cacheKey] = parent::createNumberFormatter($style, $decimals, $options, $textOptions);
        }

        return $this->_numberFormatterCache[$cacheKey];
    }
}
