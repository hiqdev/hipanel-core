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
            [['name', 'value'], 'string', 'min' => 1],
            [['name', 'value'], 'trim'],
        ];
    }

    public function isEmpty(): bool
    {
        return empty($this->name) || empty($this->value);
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel', 'Name'),
            'value' => Yii::t('hipanel', 'Value'),
        ];
    }
}
