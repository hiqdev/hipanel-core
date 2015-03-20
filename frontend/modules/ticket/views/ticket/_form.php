<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\markdown\MarkdownEditor;
use frontend\assets\iCheckAsset;
use yii\helpers\Url;

//iCheckAsset::register($this);
//$this->registerJs("$('input.icheckbox').iCheck({
//    checkboxClass: 'icheckbox_minimal-blue',
//    radioClass: 'iradio_minimal-blue'
//});", \frontend\components\View::POS_READY);
?>
<div class="box box-primary">
    <div class="box-body">
        <div class="ticket-form">

            <?php $form = ActiveForm::begin([
                'action' => $model->scenario == 'insert' ? Url::toRoute(['create']) : Url::toRoute([
                    'update',
                    'id' => $model->id
                ]),
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <!-- Properties -->
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
            </div>

            <?= $form->field($model, 'subject') ?>

            <?= $form->field($model, 'message')->widget(MarkdownEditor::classname(), [
                'height' => 300,
                'encodeLabels' => false
            ]); ?>

            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <?php
                    if (!$model->isNewRecord)
                        print $form->field($model, 'is_private')->checkbox(['class' => 'icheckbox']);
                    ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php $form::end(); ?>

        </div>
        <!-- ticket-_form -->
    </div>
</div>
