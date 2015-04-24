<?php
use frontend\assets\AutosizeAsset;
use yii\helpers\Html;
use yii\web\JsExpression;
use frontend\assets\iCheckAsset;
use yii\helpers\Url;

iCheckAsset::register($this);
AutosizeAsset::register($this);

$dopScript = <<< JS
// Init iCheck
$('input.icheck').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});
// Expand message textarea
$('.leave-comment-form textarea').one('focus', function(e) {
    $('.hidden-form-inputs').toggle();
    $(this).attr('rows', '5');
    autosize(this);
});
JS;
$this->registerJs($dopScript, \yii\web\View::POS_READY);
$this->registerCss(<<< CSS
.checkbox label {
    padding-left: 0
}
.checkbox div {
    margin-right: 5px;
}

.hidden-form-inputs {
    display: none;
}
CSS
); ?>

<?php if ($model->isNewRecord)
    print $form->field($model, 'subject'); ?>

<?= $form->field($model, 'message')->textarea(['rows' => 1, 'placeholder' => 'Leave message here']); ?>
<div class="hidden-form-inputs">
    <div class="row">
        <div class="col-md-3">
            <?php if (!$model->isNewRecord)
                print $form->field($model, 'is_private')->checkbox(['class' => 'icheck']); ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($model, 'file[]')->widget(\kartik\widgets\FileInput::className(), [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialPreviewShowDelete' => true,
                    'maxFileCount' => 5,
                    'msgFilesTooMany' => 'Number of files selected for upload ({n}) exceeds maximum allowed limit of {m}. Please retry your upload!',
                ]
            ])->label(false); ?>
        </div>
    </div>
</div>