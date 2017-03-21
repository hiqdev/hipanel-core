<?php

/**
 * @var \yii\web\View $this
 * @var \hipanel\widgets\PincodePrompt $widget
 */

use yii\helpers\Html;

?>

<div class="modal fade pincode-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?= Yii::t('hipanel', 'Enter pincode') ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?= Html::passwordInput('pincode', null, [
                        'class' => 'form-control pincode-input',
                        'placeholder' => '****',
                        'autocomplete' => 'new-password',
                    ]) ?>
                    <p class="help-block"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('hipanel', 'Close'); ?>
                </button>

                <?= Html::button(Yii::t('hipanel', 'Send'), [
                    'class' => 'btn btn-primary pincode-submit',
                    'data-toggle' => 'button',
                    'data-loading-text' => $widget->loadingText,
                ]) ?>
            </div>
        </div>
    </div>
</div>

