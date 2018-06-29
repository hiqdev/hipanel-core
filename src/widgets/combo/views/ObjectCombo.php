<?php

/** @var string $class_attribute */
/** @var string $class_attribute_name */
/** @var \yii\db\ActiveRecordInterface $model */
/** @var array $objectOptions */
/** @var string $attribute */
/** @var array $objects */

use hipanel\widgets\combo\InternalObjectCombo;
use yii\bootstrap\Html;

?>

<div class="row">
    <div class="col-md-12"
         style="display: flex; flex-direction: row; justify-content: space-between; flex-wrap: nowrap;">
        <?= Html::activeDropDownList($model, $class_attribute_name, $objectOptions, [
            'class' => 'form-control', 'prompt' => '--',
        ]) ?>
        <?= InternalObjectCombo::widget(compact('model', 'attribute', 'objects', 'class_attribute', 'class_attribute_name')) ?>
    </div>
</div>
