<?php

use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var string $scenario
 * @var string $bodyWarning
 * @var string $affectedObjects
 * @var string $panelBody
 * @var string $formatterField
 * @var string $submitButton
 * @var array $submitButtonOptions
 * @var \yii\base\Model $model
 * @var \yii\base\Model[] $models
 * @var string[] $hiddenInputs
 * @var string[] $visibleInputs
 * @var string[] $dropDownInputs
 */

$form = ActiveForm::begin([
    'id' => "bulk-{$scenario}-form",
    'action' => Url::toRoute($scenario),
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
                    'data' => array_map(fn ($model) => $model->{$formatterField}, $models),
                    'visibleCount' => count($models),
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
