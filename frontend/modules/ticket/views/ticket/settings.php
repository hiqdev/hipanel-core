<?php

use frontend\components\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

// use frontend\assets\iCheckAsset;

// iCheckAsset::register($this);
//$this->registerJs(new \yii\web\JsExpression("
//$('input.icheck').iCheck({
//    checkboxClass: 'icheckbox_minimal-blue',
//    radioClass: 'iradio_minimal-blue'
//});
//"), yii\web\View::POS_READY);

$this->title = Yii::t('app', 'Ticket Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(Yii::$app->params['pjax']); ?>
    <div class="box">
        <div class="box-header">

        </div>
        <div class="box-body">

            <p><?= Yii::t('app', 'This section allows you to manage the settings on mail alerts'); ?></p>

            <p><?= Yii::t('app', 'In this field you can specify to receive email notifications of ticket. By default, the notification is used for editing the main e-mail'); ?></p>

            <?php $form = ActiveForm::begin([
                'options' => ['data-pjax' => '1'],
            ]); ?>

            <?= $form->field($model, 'ticket_emails'); ?>

            <p>
                <?= Yii::t('app', 'If you check in the mail notification will include the text of the new message in the ticket.
                By default, the mail comes only acknowledgment of receipt of the ticket and a link to the ticket.
                WARNING! The text can include confidential information and data access'); ?>
            </p>

            <?= $form->field($model, 'send_message_text')->checkbox(); ?>

            <?= Html::submitButton(Yii::t('app', 'Save {modelClass}', [
                'modelClass' => 'Ticket Settings',
            ]), ['class' => 'btn btn-success']) ?>

            <?php $form::end(); ?>
        </div>
    </div>
<?php Pjax::end() ?>