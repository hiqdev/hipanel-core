<?php
declare(strict_types=1);

namespace hipanel\behaviors;

use hipanel\models\CustomAttribute;
use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\base\Behavior;

class CustomAttributes extends Behavior
{
    public string $attributeDataField = 'custom_data';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'attachCustomAttributes',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'attachCustomAttributes',
        ];
    }

    public function attachCustomAttributes(): void
    {
        $request = Yii::$app->request;
        $attributeModel = new CustomAttribute();
        $planAttributeData = $request->post($attributeModel->formName(), []);
        $customData['attributes'] = [];
        foreach ($planAttributeData as $planAttribute) {
            $attributeModel->load($planAttribute, '');
            if ($attributeModel->validate()) {
                $customData['attributes'][$attributeModel->name] = $attributeModel->value;
            }
        }
        $this->owner->{$this->attributeDataField} = $customData;
    }

    public function getCustomAttributes(): array
    {
        $attributes = $this->owner->{$this->attributeDataField}['attributes'] ?? [];
        $models = [];
        foreach ($attributes as $name => $value) {
            $model = new CustomAttribute(compact('name', 'value'));
            if (!$model->isEmpty()) {
                $models[] = $model;
            }
        }

        return empty($models) ? [] : $models;
    }
}
