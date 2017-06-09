<?php

use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'id' => "bulk-{$scenario}-form",
    'action' => Url::toRoute("bulk-{$scenario}"),
    'enableAjaxValidation' => false,
]);
?>

    <?php if ($bodyWarning) : ?>
        <div class="callout callout-warning">
            <h4><?= $bodyWarning ?></h4>
        </div>
    <?php endif ?>

    <div class="panel panel-default">
        <div class="panel-heading"><?= $affectedObjects ?></div>
        <div class="panel-body">
            <?php if ($panelBody === null) : ?>
                <?= ArraySpoiler::widget([
                    'data' => $models,
                    'visibleCount' => count($models),
                    'formatter' => function ($model) use ($formatterField) {
                        return $model->{$formatterField};
                    },
                    'delimiter' => ',&nbsp; ',
                ]); ?>
            <?php else : ?>
                <?= $panelBody ?>
            <?php endif ?>
        </div>
    </div>

    <?php foreach ($models as $item) : ?>
        <?php foreach ($hiddenInputs as $hiddenInput) : ?>
            <?= Html::activeHiddenInput($item, "[$item->id]{$hiddenInput}") ?>
        <?php endforeach ?>
    <?php endforeach ?>

    <div class="row">
        <?php foreach ($dropDownInputs as $dropDownName => $dropDownValues) : ?>
            <div class="col-sm-6">
                <?= $form->field($model, $dropDownName)->dropDownList($dropDownValues, [
                    'id' => "{$scenario}-{$dropDownName}",
                    'name' => $dropDownName,
                ]) ?>
            </div>
        <?php endforeach ?>
        <?php foreach ($visibleInputs as $visibleInput) : ?>
            <div class="col-sm-6">
                <?= $form->field($model, $visibleInput)->textInput([
                    'id' => "{$scenario}-comment",
                    'name' => $visibleInput,
                ]) ?>
            </div>
        <?php endforeach ?>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton($submitButton, $submitButtonOptions) ?>
    </div>
<?php ActiveForm::end() ?>
