<?
use yii\helpers\Html;
use frontend\widgets\HiBox;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use frontend\models\Ref;
use yii\helpers\ArrayHelper;
use frontend\components\Re;
use kartik\markdown\MarkdownEditor;
use yii\helpers\Url;

$this->registerjs('$("button[data-widget=\'collapse\']").click();', yii\web\View::POS_READY);
?>
<div class="ticket-form">

    <?php $form = ActiveForm::begin([
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
            <?php \yii\helpers\VarDumper::dump($model->topic, 10, true); ?>
            <?= $form->field($model, 'topic')->widget(Select2::classname(), [
                'data' => array_merge(["" => ""], ArrayHelper::map(Ref::find()->where(['gtype' => 'topic,ticket'])->getList(), 'gl_key', function ($o) { return Re::l($o->gl_value); })),
                'options' => ['placeholder' => 'Select a topic ...', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?php if ($model->scenario != 'update') : ?>
                <?= $form->field($model, 'topic')->widget(Select2::classname(), [
                    'data' => array_merge(["" => ""], ArrayHelper::map(Ref::find()->where(['gtype' => 'topic,ticket'])->getList(), 'gl_key', function ($o) { return Re::l($o->gl_value); })),
                    'options' => ['placeholder' => 'Select a topic ...', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            <?php else : ?>
                <?= $form->field($model, 'topic')->widget(Select2::classname(), [
                    'data' => array_merge(["" => ""], ArrayHelper::map(Ref::find()->where(['gtype' => 'topic,ticket'])->getList(), 'gl_key', function ($o) { return Re::l($o->gl_value); })),
                    'options' => ['placeholder' => 'Select a topic ...', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            <?php endif; ?>
            <?= $form->field($model, 'state')->widget(Select2::classname(), [
                'data' => array_merge(["" => ""], ArrayHelper::map(Ref::find()->where(['gtype' => 'state,ticket'])->getList(), 'gl_key', function ($o) { return Re::l($o->gl_value); })),
                'options' => ['placeholder' => 'Select a state ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?= $form->field($model, 'priority')->widget(Select2::classname(), [
                'data' => array_merge(["" => ""], ArrayHelper::map(Ref::find()->where(['gtype' => 'type,priority'])->getList(), 'gl_key', function ($o) { return Re::l($o->gl_value); })),
                'options' => ['placeholder' => 'Select a priority ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'responsible_id')->widget(Select2::classname(), [
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
            ]); ?>

            <?= $form->field($model, 'recipient_id')->widget(Select2::classname(), [
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
            ]); ?>
            <?php /*print $form->field($model, 'watcher')*/ ?>

        </div>
    </div>
    <?php /* $box = HiBox::end() */ ?>


    <?= $form->field($model, 'subject') ?>
    <?= $form->field($model, 'message')->widget(MarkdownEditor::classname(), [
            'height' => 300,
            'encodeLabels' => false
        ]);; ?>

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