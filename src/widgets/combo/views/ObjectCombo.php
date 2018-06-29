<?php

/** @var string $class_type_attribute */

/** @var string $class_real_attribute */

/** @var \yii\db\ActiveRecordInterface $model */

/** @var array $objectOptions */

/** @var string $attribute */

/** @var array $availableObjects */

use hipanel\widgets\combo\InternalObjectCombo;
use yii\bootstrap\Html;

?>

<div class="row">
    <div class="col-md-12"
         style="display: flex; flex-direction: row; justify-content: space-between; flex-wrap: nowrap;">
        <?= Html::activeDropDownList($model, $class_real_attribute, $objectOptions, [
            'class' => 'form-control', 'prompt' => '--',
        ]) ?>
        <?=
        InternalObjectCombo::widget([
            'model' => $model,
            'attribute' => $attribute,
            'objectsOptions' => $availableObjects,
            'currentObjectType' => $model->{$class_attribute},
            'currentObjectAttributeName' => $class_real_attribute,
        ]) ?>
    </div>
</div>
