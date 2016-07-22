<?php

use hipanel\helpers\Url;
use hipanel\models\Reminder;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'reminder-form-' . $model->object_id,
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'update']),
]) ?>

<?= Html::activeHiddenInput($model, 'object_id') ?>

<?= $form->field($model, "type")->radioList([
    Reminder::REMINDER_TYPE_SITE => Yii::t('hipanel/reminder', 'To site'),
    Reminder::REMINDER_TYPE_MAIL => Yii::t('hipanel/reminder', 'By mail'),
]) ?>

<?= $form->field($model, "periodicity")->dropDownList($periodicityOptions) ?>

<?= $form->field($model, "from_time") ?>

<?php
if (Yii::$app->user->can('support')) {
    $model->client_id = Yii::$app->user->identity->id;
    print $form->field($model, "client_id")->widget(ClientCombo::class);
}
?>

<?= $form->field($model, "message")->textarea(['rows' => 4]) ?>

<?= Html::submitButton(Yii::t('hipanel', 'Submit'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
