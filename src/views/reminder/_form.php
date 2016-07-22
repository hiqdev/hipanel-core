<?php

use hipanel\helpers\Url;
use hipanel\models\Reminder;
use hipanel\modules\client\widgets\combo\ClientCombo;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'reminder-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'update']),
]) ?>

<?= $form->field($model, "to_site")->checkboxList([
    Reminder::REMINDER_TYPE_SITE => Yii::t('hipanel/reminder', 'By site'),
    Reminder::REMINDER_TYPE_MAIL => Yii::t('hipanel/reminder', 'By mail'),
]) ?>
<?= $form->field($model, "periodicity")->dropDownList([]) ?>
<?= $form->field($model, "from_time")->widget(DateTimePicker::class) ?>
<?php
$model->client_id = Yii::$app->user->identity->id;
$form->field($model, "client_id")->widget(ClientCombo::class)
?>
<?= $form->field($model, "message")->textarea(['rows' => 4]) ?>

<?= Html::submitButton(Yii::t('hipanel', 'Submit')) ?>

<?php ActiveForm::end() ?>
