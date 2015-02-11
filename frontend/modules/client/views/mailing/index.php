<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\client\models\Mailing */
/* @var $form ActiveForm */
?>
<div class="client-mailing-index">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from') ?>
    <?= $form->field($model, 'subject') ?>
    <?= $form->field($model, 'message')->textarea(['rows'=>6]) ?>
    <?= $form->field($model, 'types')->dropDownList(['newsletters'=>'Newsletters','commercial'=>'Commercial']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- client-mailing-index -->