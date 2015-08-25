<?php

/**
 * Class FilterValidator
 * @inheritdoc
 */
class FilterValidator extends \yii\validators\FilterValidator {
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!$this->skipOnArray || !is_array($value)) {
            $model->$attribute = call_user_func($this->filter, $value, $model);
        }
    }

}