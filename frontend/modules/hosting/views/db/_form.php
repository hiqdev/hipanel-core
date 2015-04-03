<?php

/* @var $this frontend\components\View */
/* @var $model frontend\modules\hosting\models\Db */
/* @var $type string */

use frontend\components\widgets\PasswordInput;
use frontend\components\widgets\Combo2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use Yii;

?>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="ticket-form" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
                        <?php $form = ActiveForm::begin([
                            'action' => $model->isNewRecord ? Url::to('create') : Url::toRoute([
                                'update',
                                'id' => $model->id
                            ]),
                        ]);
                        ?>
                        <!-- Properties -->

                        <?php
                        print $form->field($model, 'client_id')->widget(Combo2::className(), ['type' => 'client']);
                        print $form->field($model, 'server_id')->widget(Combo2::className(), ['type' => 'server']);
                        print $form->field($model, 'account_id')->widget(Combo2::className(), ['type' => 'account']);
                        print $form->field($model, 'service_id')->widget(Combo2::className(), ['type' => 'dbService']);

                        print $form->field($model, 'name');
                        print $form->field($model, 'password')->widget(PasswordInput::className());

                        print $form->field($model, 'description');
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