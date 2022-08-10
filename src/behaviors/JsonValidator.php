<?php

declare(strict_types=1);

namespace hipanel\behaviors;

use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\validators\Validator;

class JsonValidator extends Validator
{
    public bool $merge = false;

    /**
     * Map json error constant to message
     * @see: http://php.net/manual/ru/json.constants.php
     * @var array
     */
    public array $errorMessages = [];

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!$value instanceof JsonField) {
            try {
                $new = new JsonField($value);
                if ($this->merge) {
                    /** @var BaseActiveRecord $model */
                    $old = new JsonField($model->getOldAttribute($attribute));
                    $new = new JsonField(array_merge($old->toArray(), $new->toArray()));
                }
                $model->$attribute = $new;
            } catch (InvalidArgumentException $e) {
                $this->addError($model, $attribute, $this->getErrorMessage($e));
                $model->$attribute = new JsonField();
            }
        }
    }

    /**
     * @param \Exception $exception
     * @return string
     */
    protected function getErrorMessage(\Exception $exception): string
    {
        $code = $exception->getCode();
        if (isset($this->errorMessages[$code])) {
            return $this->errorMessages[$code];
        }

        return $exception->getMessage();
    }
}
