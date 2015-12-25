<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

trait ModelTrait
{
    /**
     * Overrides default [[attributes()]] method.
     * Extracts attributes from the validation rules described in [[rules()]] method
     *
     * @return array
     */
    public function attributes () {
        $attributes = \yii\base\Model::attributes();
        foreach (static::rules() as $d) {
            if (is_string(reset($d))) continue;
            foreach (reset($d) as $k) $attributes[$k] = $k;
        }
        return array_values($attributes);
    }
}
