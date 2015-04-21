<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\markdown\MarkdownEditor;
use frontend\assets\iCheckAsset;
use yii\helpers\Url;

iCheckAsset::register($this);

$dopScript = "
// Init iCheck
$('input.icheck').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});
// Expand message textarea
$('#leave-comment-form').expandingInput({
    target: 'textarea',
    hidden_content: '> div',
    placeholder: 'Write message',
    onAfterExpand: function () {
        $('#leave-comment-form textarea').attr('rows', '3').autosize();
    }
});
";
$this->registerCss(".checkbox label {padding-left: 0}");
$this->registerJs($dopScript, \yii\web\View::POS_READY);
//iCheckAsset::register($this);
//$this->registerJs("$('input.icheckbox').iCheck({
//    checkboxClass: 'icheckbox_minimal-blue',
//    radioClass: 'iradio_minimal-blue'
//});", \frontend\components\View::POS_READY);
?>

<div class="ticket-form">
    <?php $form = ActiveForm::begin([
        'action' => $model->scenario == 'insert' ? Url::toRoute(['create']) : Url::toRoute([
            'update',
            'id' => $model->id
        ]),
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?php
    if ($model->isNewRecord)
        print $form->field($model, 'subject');
    ?>

    <?= $form->field($model, 'message')->widget(MarkdownEditor::classname(), [
        'height' => 300,
        'encodeLabels' => false,
        'toolbar' => [
            [
                'buttons' => [
                    MarkdownEditor::BTN_BOLD => ['icon' => 'bold', 'title' => 'Bold'],
                    MarkdownEditor::BTN_ITALIC => ['icon' => 'italic', 'title' => 'Italic'],
                ]
            ],
            [
                'buttons' => [
                    MarkdownEditor::BTN_MAXIMIZE => ['icon' => 'fullscreen', 'title' => 'Fullscreen'],
                ],
                'options' => ['class' => 'pull-right']
            ],
        ]
    ]); ?>



    <div class="form-group">
        <div class="pull-left">
            <?php if (!$model->isNewRecord)
                print $form->field($model, 'is_private')->checkbox(['class' => 'icheck']); ?>
        </div>
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary pull-right']); ?>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#advancedOpt" aria-expanded="false"
                               class="collapsed">
                                Advanced options
                            </a>
                        </h4>
                    </div>
                    <div id="advancedOpt" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    if ($model->isNewRecord)
                                        $model->topics = 'general';
                                    else
                                        $model->topics = array_keys($model->topics);
                                    print $form->field($model, 'topics')->widget(Select2::classname(), [
                                        'data' => array_merge(["" => ""], $topic_data),
                                        'options' => ['placeholder' => 'Select a topics ...', 'multiple' => true],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ]); ?>
                                    <?php
                                    if ($model->isNewRecord)
                                        $model->state = 'opened';
                                    print $form->field($model, 'state')->widget(Select2::classname(), [
                                        'data' => array_merge(["" => ""], $state_data),
                                        'options' => ['placeholder' => 'Select a state ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ]); ?>
                                    <?php if ($model->scenario == 'insert') : ?>
                                        <?php
                                        if ($model->isNewRecord)
                                            $model->priority = 'medium';
                                        print $form->field($model, 'priority')->widget(Select2::classname(), [
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

                                    <?php
                                    if ($model->isNewRecord)
                                        $model->recipient_id = \Yii::$app->user->identity->id;

                                    print $form->field($model, 'recipient_id')->widget(Select2::classname(), [
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
                                </div>
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
                                    <?php if ($model->scenario != 'answer') : ?>
                                        <?= $form->field($model, 'spent')->widget(kartik\widgets\TimePicker::className(), [
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 1,
                                                'hourStep' => 1,
                                                'defaultTime' => '00:00',
                                            ]
                                        ]); ?>
                                    <?php else : ?>
                                        <?= $form->field($model, 'answer_spent')->widget(kartik\widgets\TimePicker::className(), [
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 1,
                                                'hourStep' => 1,
                                                'defaultTime' => '00:00',
                                            ]
                                        ])->label(Yii::t('app', 'Spen time')); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $form::end(); ?>

</div>
<!-- ticket-_form -->