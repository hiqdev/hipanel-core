<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
$model = $searchModel;
/* @var $this yii\web\View */
/* @var $model app\modules\thread\models\ThreadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-search row" style="margin-bottom: 20px; display: none;">

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
        <?php echo $form->field($model, 'state') ?>
    </div>

    <div class="col-md-4">
        <?php echo $form->field($model, 'author_id')->widget(\frontend\widgets\Select2::classname(),['url' => Url::to(['client-list'])]) ?>
        <?php echo $form->field($model, 'responsible_id')->widget(\frontend\widgets\Select2::classname(),['url' => Url::to(['client-list'])]) ?>
        <?php echo $form->field($model, 'topic') ?>
    </div>

    <div class="col-md-4">
        <?php echo $form->field($model, 'recipient_id')->widget(\frontend\widgets\Select2::classname(),['url' => Url::to(['client-list'])]) ?>
        <?php echo $form->field($model, 'priority')->dropDownList(array_merge(['' => ''], $priority_data)) ?>
        <?php echo $form->field($model, 'watchers') ?>
    </div>


    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'answer_message') ?>


    <div class="col-md-12" >
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>