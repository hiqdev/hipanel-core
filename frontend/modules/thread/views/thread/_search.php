<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
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

        <?php echo $form->field($model, 'responsible_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Search for a responsible ...'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['manager-list']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                    'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                ],
                'initSelection' => new JsExpression('function (elem, callback) {
                    var id=$(elem).val();
                    $.ajax("' . Url::to(['manager-list']) . '?id=" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data.results);
                    });
                }')
            ],
        ]) ?>

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