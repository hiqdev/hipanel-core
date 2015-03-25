<?php

/* @var $this frontend\components\View */
/* @var $model frontend\modules\hosting\models\Account */
/* @var $type string */

use frontend\components\widgets\PasswordInput;
use frontend\components\widgets\Combo2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use Yii;

$action = [
    'user'    => 'create',
    'ftponly' => 'create-ftp'
]

?>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-danger">
                <div class="box-header">
                    <?php if ($model->scenario == 'insert_user') { ?>
                        <h4><?= Yii::t('app', 'Created account will have access via SSH and FTP to the server') ?></h4>
                    <?php } ?>
                </div>
                <div class="box-body">
                    <div class="ticket-form" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
                        <?php $form        = ActiveForm::begin([
                            'action' => in_array($type, $model->getKnownTypes()) ? Url::toRoute($action[$type]) : Url::toRoute([
                                'update',
                                'id' => $model->id
                            ]),
                        ]);
                        $model->sshftp_ips = $model->getSshFtpIpsList();
                        print Html::activeHiddenInput($model, 'type', ['value' => 'user']);
                        ?>
                        <!-- Properties -->

                        <?php
                        print $form->field($model, 'client_id')->widget(Combo2::className(), ['type' => 'client']);
                        print $form->field($model, 'server_id')->widget(Combo2::className(), ['type' => 'server']);

                        print $form->field($model, 'login');
                        print $form->field($model, 'password')->widget(PasswordInput::className());

                        print $form->field($model, 'sshftp_ips')
                                   ->hint(Yii::t('app', 'Access to the account is opened by default. Please input the IPs, for which the access to the server will be granted'))
                                   ->input('text', [
                                       'data' => [
                                           'title'   => Yii::t('app', 'IP restrictions'),
                                           'content' => Yii::t('app', 'Text about IP restrictions'),
                                       ]
                                   ]);
                        ?>

                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

            <!-- ticket-_form -->
        </div>
    </div>

<?php

$this->registerJs("
    $('#account-sshftp_ips').popover({placement: 'top', trigger: 'focus'});
");