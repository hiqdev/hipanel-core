<?php

/* @var $this frontend\components\View */

use \frontend\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
    <div class="ticket-form" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
        <?php $form        = ActiveForm::begin([
            'action' => $model->scenario == 'insert' ? Url::toRoute(['create']) : Url::toRoute([
                'update',
                'id' => $model->id
            ]),
        ]);
        $model->sshftp_ips = $model->getSshFtpIpsList();
        print Html::activeHiddenInput($model, 'type', ['value' => 'user']);
        ?>
        <!-- Properties -->
        <div class="row">
            <div class="col-md-4">
                <?php
                print $form->field($model, 'server_id')->widget(Select2::classname(), [
                    'attribute' => 'server_id',
                    'model'     => $model,
                    'url'       => Url::to(['/server/server/list'])
                ]);
                print $form->field($model, 'login');

                print $form->field($model, 'password', [
                    'inputTemplate' => <<<HTML
                <div class="input-group">
                    {input}
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default show-password"><span class="glyphicon glyphicon-eye-open"></span></button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false">Action <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                </div>
HTML
                ])->passwordInput();

                print $form->field($model, 'sshftp_ips');
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div><!-- ticket-_form -->


<?php

$this->registerJs("
    $('.show-password').click(function() {
        var input = $(this).closest('.form-group').find('input');
        var type = input.attr('type');

        if (type == 'password') {
            input.attr('type', 'text');
            $(this).find('span').removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
        } else {
            input.attr('type', 'password');
            $(this).find('span').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
        }
    });
");