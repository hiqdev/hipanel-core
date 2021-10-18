<?php
declare(strict_types=1);

namespace hipanel\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;

/**
 * @property string|null name
 * @property string|null value
 */
class CustomAttribute extends Model
{
    use ModelTrait;

    public function rules()
    {
        return [
            [['name', 'value'], 'string'],
            [['name', 'value'], 'trim'],
        ];
    }

    public function isEmpty(): bool
    {
        return empty($this->name) || empty($this->value);
    }

    public function stringValue(): string
    {
        if (!is_array($this->value)) {
            return (string)$this->value;
        }

        return (string)json_encode($this->value, JSON_THROW_ON_ERROR);
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel', 'Name'),
            'value' => Yii::t('hipanel', 'Value'),
        ];
    }
}
