<?php
declare(strict_types=1);

namespace hipanel\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;
use yii\helpers\Html;

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
        return empty($this->name) || $this->value === '';
    }

    public function stringValue(): string
    {
        if (is_bool($this->value)) {
            return json_encode($this->value);
        }

        if (!is_array($this->value)) {
            return Html::encode($this->value);
        } else if (array_is_list($this->value) && count($this->value) > 0) {
            return implode('<br>', array_map([Html::class, 'encode'], $this->value));
        }

        $content = '';
        foreach ($this->value as $key => $value) {
            $content .= Html::encode($key) . ': ' . Html::encode($value) . '<br>';
        }
        return $content;
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel', 'Name'),
            'value' => Yii::t('hipanel', 'Value'),
        ];
    }
}
