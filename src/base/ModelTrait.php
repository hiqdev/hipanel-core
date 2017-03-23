<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
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
        foreach (self::rules() as $rule) {
            if (is_string(reset($rule))) {
                continue;
            }
            foreach (reset($rule) as $attribute) {
                if (substr_compare($attribute, '!', 0, 1) === 0) {
                    $attribute = mb_substr($attribute, 1);
                }
                $attributes[$attribute] = $attribute;
            }
        }
        return array_values($attributes);
    }
}
