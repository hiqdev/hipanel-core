<?php
/* @var $this yii\web\View */
/* @var $model frontend\modules\hosting\models\account */

use frontend\modules\object\widgets\RequestState;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;


$this->title                   = Yii::t('app', 'Account {modelClass}', [
    'modelClass' => $model->login,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-5">
        <div class="event-view">
            <?= DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    [
                        'attribute' => 'state',
                        'format'    => 'raw',
                        'value'     => RequestState::widget([
                            'model'         => $model,
                            'clientOptions' => [
                                'afterChange' => new JsExpression("function () { $.pjax.reload('#content-pjax', {'timeout': 0});}")
                            ]
                        ])
                    ],
                    [
                        'attribute' => 'device',
                        'format'    => 'html',
                        'value'     => \yii\helpers\Html::a($model->device, [
                            '/server/server/view',
                            'id' => $model->device_id
                        ])
                    ],
                    'ip',
                    'login',
                    [
                        'attribute' => 'password',
                        'format'    => 'raw',
                        'value'     => Yii::t('app', 'Only able to change')
                    ],
                    [
                        'attribute' => 'sshftp_ips',
                        'format'    => 'raw',
                        'value'     => \frontend\widgets\ArraySpoiler::widget([
                            'data'          => $model->sshftp_ips,
                            'visible_count' => 3
                        ])
                    ]
                ]
            ]) ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?= \Yii::t('app', 'Manage account') ?></div>
                    <div class="panel-body">
                        <?php
                        $model->scenario = 'set-password';
                        $form            = \yii\bootstrap\ActiveForm::begin([
                            'action'  => Url::toRoute(['set-password', 'id' => $model->id]),
                            'options' => [
                                'data'            => ['pjax' => 1],
                                'class'           => 'inline',
                            ]
                        ]);

                        $this->registerJs("$('#{$form->id}').on('beforeSubmit', function (event) {
                            if ($(this).data('yiiActiveForm').validated) {
                                return $(this).find('[type=\"submit\"]').button('loading');
                            }
                        });");

                        Modal::begin([
                            'toggleButton'  => [
                                'label'    => Yii::t('app', 'Change password'),
                                'class'    => 'btn btn-default',
                                'disabled' => !$model->isOperable(),
                            ],
                            'header'        => Html::tag('h4', Yii::t('app', 'Enter a new password')),
                            'headerOptions' => ['class' => 'label-info'],
                            'footer'        => Html::submitButton(Yii::t('app', 'Change'), [
                                'class'             => 'btn btn-warning',
                                'data-loading-text' => Yii::t('app', 'Changing...'),
                            ])
                        ]);
                        ?>
                        <div class="callout callout-warning">
                            <h4><?= Yii::t('app', 'This will immediately terminate all sessions of the user!') ?></h4>
                        </div>

                        <?php echo $form->field($model, 'password')
                                        ->widget(\frontend\widgets\PasswordInput::className())
                                        ->label(false);
                        echo $form->field($model, 'login')->hiddenInput()->label(false);

                        Modal::end();
                        $form->end();
                        ?>

                        <?php
                        $model->scenario = 'update';
                        $form            = \yii\bootstrap\ActiveForm::begin([
                            'action'  => Url::toRoute(['set-allowed-ips', 'id' => $model->id]),
                            'options' => [
                                'data'  => ['pjax' => 1],
                                'class' => 'inline',
                            ],
                        ]);

                        $this->registerJs("$('#{$form->id}').on('beforeSubmit', function (event) {
                            if ($(this).data('yiiActiveForm').validated) {
                                return $(this).find('[type=\"submit\"]').button('loading');
                            }
                        });");

                        Modal::begin([
                            'toggleButton'  => [
                                'label'    => Yii::t('app', 'Change SSH/FTP IPs'),
                                'class'    => 'btn btn-default',
                                'disabled' => !$model->isOperable(),
                            ],
                            'header'        => Html::tag('h4', Yii::t('app', 'Enter new restrictions')),
                            'headerOptions' => ['class' => 'label-info'],
                            'footer'        => Html::submitButton(Yii::t('app', 'Change'), [
                                'class'             => 'btn btn-warning',
                                'data-loading-text' => Yii::t('app', 'Changing...'),
                            ])
                        ]);
                        ?>
                        <div class="callout callout-warning">
                            <h4><?= Yii::t('app', 'This will immediately terminate all sessions of the user!') ?></h4>
                        </div>

                        <?php echo $form->field($model, 'sshftp_ips');

                        Modal::end();
                        $form->end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>