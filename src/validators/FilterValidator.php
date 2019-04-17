<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

/**
 * Class FilterValidator
 * {@inheritdoc}
 */
class FilterValidator extends \yii\validators\FilterValidator
{
    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!$this->skipOnArray || !is_array($value)) {
            $model->$attribute = call_user_func($this->filter, $value, $model);
        }
    }
}
