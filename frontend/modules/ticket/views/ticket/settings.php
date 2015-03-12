<?php

use yii\widgets\Pjax;

?>
<?php Pjax::begin(); ?>
<div class="box">
    <div class="box-header">
        <?= yii\helpers\Html::a(Yii::t('app', 'Save {modelClass}', [
            'modelClass' => 'Ticket Settings',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body">
        <p>This section allows you to manage the settings on mail alerts </p>
        <p>In this field you can specify to receive email notifications of ticket. By default, the notification is used for editing the main e-mail</p>
        <form class="form">

            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" class="form-control" placeholder="Email" value="<?= $settings['ticket_emails'] ?>">
            </div>

            <p>
                If you check in the mail notification will include the text of the new message in the ticket.
                By default, the mail comes only acknowledgment of receipt of the ticket and a link to the ticket.
                WARNING! The text can include confidential information and data access
            </p>

            <!-- checkbox -->
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" checked="<?= $settings['send_message_text'] ? 'checked' : '' ?>" />
                        Send message text
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
<?php Pjax::end() ?>
