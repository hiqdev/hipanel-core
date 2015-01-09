<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\thread\models\ThreadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-search" style="margin-bottom: 20px; display: none;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-md-4">
        <?= $form->field($model, 'subject') ?>

        <div class="form-group">
        <?= Html::tag('label', 'Date range', ['class'=>'control-label']); ?>
        <?= DatePicker::widget([
            'model'=>$model,
            'attribute' => 'time_from',
            // 'value' => date('d-m-Y'),
            'type' => DatePicker::TYPE_RANGE,
            'attribute2' => 'time_till',
            // 'value2' => date('d-m-Y'),
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd-mm-yyyy'
            ]
        ]); ?>
        </div>
        <?php echo $form->field($model, 'status') ?>
    </div>

    <div class="col-md-4">
        <?php echo $form->field($model, 'author_id') ?>
        <?php echo $form->field($model, 'responsible_id') ?>
        <?php echo $form->field($model, 'topic') ?>
    </div>

    <div class="col-md-4">
        <?php echo $form->field($model, 'recipient_id') ?>
        <?php echo $form->field($model, 'priority') ?>
        <?php echo $form->field($model, 'watchers') ?>
    </div>

    <?php // echo $form->field($model, 'author_seller') ?>

    <?php // echo $form->field($model, 'recipient') ?>

    <?php // echo $form->field($model, 'recipient_seller') ?>

    <?php // echo $form->field($model, 'replier_id') ?>

    <?php // echo $form->field($model, 'replier') ?>

    <?php // echo $form->field($model, 'replier_seller') ?>

    <?php // echo $form->field($model, 'replier_name') ?>

    <?php // echo $form->field($model, 'responsible') ?>

    <?php // echo $form->field($model, 'spent') ?>

    <?php // echo $form->field($model, 'answer_count') ?>

    <?php // echo $form->field($model, 'reply_time') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'a_reply_time') ?>

    <?php // echo $form->field($model, 'elapsed') ?>

    <?php // echo $form->field($model, 'add_tag_ids') ?>

    <?php // echo $form->field($model, 'file_ids') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'answer_message') ?>

    <?php // echo $form->field($model, 'is_private') ?>

    <?php // echo $form->field($model, 'anonym_email') ?>

    <?php // echo $form->field($model, 'anonym_seller') ?>


    <div class="form-group" style="margin-top: 20px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>