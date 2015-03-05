<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\markdown\MarkdownEditor;
use yii\helpers\Url;
//use frontend\widgets\HiBox;
//use frontend\models\Ref;
//use yii\helpers\ArrayHelper;
//use frontend\components\Re;

//\yii\helpers\VarDumper::dump($model, 10, true);
//$this->registerjs('$("button[data-widget=\'collapse\']").click();', yii\web\View::POS_READY);

// client - message, title, files
// all - > all

?>
<div class="box box-primary">
<div class="box-body">
<div class="ticket-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->scenario == 'insert' ? Url::toRoute(['create']) : Url::toRoute(['update', 'id' => $model->id]),
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?php /* $box = HiBox::begin([
        'title'=>'<i class="fa fa-cogs"></i>&nbsp;&nbsp;Properties',
        'buttonsTemplate'=>'{collapse}',
        'options'=>[]
    ]) */ ?>
    <!-- Properties -->
    <div class="row">
        <div class="col-md-6">
            <?php
            if ($model->isNewRecord) $model->topic = 'general';
            print $form->field($model, 'topic')->widget(Select2::classname(), [
                'data' => array_merge(["" => ""], $topic_data),
                'options' => ['placeholder' => 'Select a topic ...', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?= $form->field($model, 'state')->widget(Select2::classname(), [
                'data' => array_merge(["" => ""], $state_data),
                'options' => ['placeholder' => 'Select a state ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?php if ($model->scenario == 'insert') : ?>
            <?= $form->field($model, 'priority')->widget(Select2::classname(), [
                'data' => array_merge(["" => ""], $priority_data),
                'options' => ['placeholder' => 'Select a priority ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'responsible_id')->widget(Select2::classname(), [
                'options' => ['placeholder' => 'Search for a responsible ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to(['/client/client/client-all-list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                                                            var id=$(elem).val();
                                                            $.ajax("' . Url::to(['/client/client/client-all-list']) . '?id=" + id, {
                                                                dataType: "json"
                                                            }).done(function(data) {
                                                                callback(data.results);
                                                            });
                                                        }')
                ],
            ]); ?>

            <?= $form->field($model, 'recipient_id')->widget(Select2::classname(), [
                'options' => ['placeholder' => 'Search for a responsible ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to(['/client/client/can-manage-list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                                                            var id=$(elem).val();
                                                            $.ajax("' . Url::to(['/client/client/can-manage-list']) . '?id=" + id, {
                                                                dataType: "json"
                                                            }).done(function(data) {
                                                                callback(data.results);
                                                            });
                                                        }')
                ],
            ]); ?>
            <?php if ($model->scenario == 'insert') : ?>
            <?= $form->field($model, 'watchers')->widget(Select2::classname(), [
                'options' => ['placeholder' => 'Select watchers ...', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'multiple' => true,
                    'ajax' => [
                        'url' => Url::to(['/client/client/can-manage-list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                                                            var id=$(elem).val();
                                                            $.ajax("' . Url::to(['/client/client/can-manage-list']) . '?id=" + id, {
                                                                dataType: "json"
                                                            }).done(function(data) {
                                                                callback(data.results);
                                                            });
                                                        }')
                ],
            ]); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php /* $box = HiBox::end() */ ?>


    <?= $form->field($model, 'subject') ?>
    <?=  $form->field($model, 'message')->widget(MarkdownEditor::classname(), [
        'height' => 300,
        'encodeLabels' => false
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'file[]')->widget(\kartik\widgets\FileInput::className(), [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialPreviewShowDelete' => true,
                    'maxFileCount' => 5,
                    'msgFilesTooMany' => 'Number of files selected for upload ({n}) exceeds maximum allowed limit of {m}. Please retry your upload!',
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'spent')->widget(kartik\widgets\TimePicker::className(), [
                'pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                    'minuteStep' => 1,
                    'hourStep' => 1,
                    'defaultTime' => '00:00',
                ]
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div><!-- ticket-_form -->
</div>
</div>
