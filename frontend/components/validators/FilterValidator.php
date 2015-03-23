<?php
/**
 * Created by PhpStorm.
 * User: SilverFire
 * Date: 19.03.2015
 * Time: 13:54
 */


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