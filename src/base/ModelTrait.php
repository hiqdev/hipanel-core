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

trait ModelTrait
{
    /**
     * Overrides default [[attributes()]] method.
     * Extracts attributes from the validation rules described in [[rules()]] method.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = \yii\base\Model::attributes();
        foreach (self::rules() as $d) {
            if (is_string(reset($d))) {
                continue;
            }
            foreach (reset($d) as $k) {
                $attributes[$k] = $k;
            }
        }
        return array_values($attributes);
    }
}
