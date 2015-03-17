<?php

/* @var $this frontend\components\View */
/* @var $model frontend\modules\hosting\models\Account */
/* @var $type string */

use frontend\components\widgets\Select2;
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
                        print $form->field($model, 'client_id');

                        print $form->field($model, 'server_id')->widget(Select2::classname(), [
                            'attribute' => 'server_id',
                            'model'     => $model,
                            'url'       => Url::to(['/server/server/list'])
                        ]);
                        print $form->field($model, 'login');

                        $spell = [
                            'random'   => Yii::t('app', 'Random'),
                            'good'     => Yii::t('app', 'Good'),
                            'better'   => Yii::t('app', 'Better'),
                            'the best' => Yii::t('app', 'The best'),
                        ];

                        print $form->field($model, 'password')
                                   ->widget(\frontend\components\widgets\PasswordInput::className());

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


$this->registerJs(<<<JS

$(document).ready(function () {
	$.fn.hiSelect2Config().add('client', {
		name: 'client',
		type: 'client',
		pluginOptions: {
			allowClear: true,
			initSelection: function (element, callback) {
				var data = {
					id: element.val(),
					text: element.attr('data-init-text') ? element.attr('data-init-text') : element.val()
				};

				callback(data);
			},
			ajax: {
				url: "/clients/clients/list",
				dataType: 'json',
				quietMillis: 400,
				data: function (term) {
					var form = $(this).data('field').form;
					return form.createFilter({'client_like': {format: term}});
				},
				results: function (data) {
					var ret = [];
					if (!data.error) {
						$.each(data, function (index, value) {
							ret.push({id: value, text: value});
						});
					}
					return {results: ret};
				}
			},
			onChange: function (e) {
				return $(this).data('field').form.update(e);
			}
		},
		onUpdate: function (e) {
            alert(1);
        }
	});

    $('#w0').hiSelect2().register('#account-client_id', 'client', {ajax:{quietMillis: 400}});
});

JS
);