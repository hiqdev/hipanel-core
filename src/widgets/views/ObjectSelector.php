<?php

/** @var string $object_type_attribute */

/** @var string $object_type_real_attribute */

/** @var \yii\db\ActiveRecordInterface $model */

/** @var array $objectOptions */

/** @var string $attribute */

/** @var array $availableObjects */

use hipanel\widgets\combo\ObjectCombo;
use yii\bootstrap\Html;

?>

<div class="row">
    <div class="col-md-12"
         style="display: flex; flex-direction: row; justify-content: space-between; flex-wrap: nowrap;">
        <?= Html::activeDropDownList($model, $object_type_real_attribute, $objectOptions, [
            'class' => 'form-control', 'prompt' => '--',
        ]) ?>
        <?=
        ObjectCombo::widget([
            'model' => $model,
            'attribute' => $attribute,
            'objectsOptions' => $availableObjects,
            'currentObjectType' => $model->{$object_type_attribute},
            'currentObjectAttributeName' => $object_type_real_attribute,
        ]) ?>
    </div>
</div>
