<?php

/** @var string $class_attribute */
/** @var string $class_attribute_name */
/** @var \yii\db\ActiveRecordInterface $model */
/** @var array $classOptions */
/** @var string $attribute */
/** @var string $selectedAttributeName */

/** @var array $classes */
use hipanel\widgets\combo\InternalObjectCombo;
use yii\bootstrap\Html;

$comboOptions = [
    'model' => $model,
    'attribute' => $attribute,
    'classes' => $classes,
    'class_attribute' => $class_attribute,
    'class_attribute_name' => $class_attribute_name,
];

if ($selectedAttributeName !== null) {
    $comboOptions['current'] = [Html::getAttributeValue($model, $attribute) => $model->$selectedAttributeName];
}

?>

<div class="row">
    <div class="col-md-12"
         style="display: flex; flex-direction: row; justify-content: space-between; flex-wrap: nowrap;">
        <?= Html::activeDropDownList($model, $class_attribute_name, $classOptions, [
            'class' => 'form-control',
            'prompt' => '--',
            'style' => 'width: 30%;',
        ]) ?>
        <?= InternalObjectCombo::widget($comboOptions) ?>
    </div>
</div>
