<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\Url;

$this->registerJs("
// Button handle
$('.search-button').click(function(){
    $('.thread-search').toggle();
    return false;
});
//
$('#search-form-thread-pjax').on('pjax:end', function() {
    $.pjax.reload({container:'#thread-grid-pjax', timeout: false});  //Reload GridView
});
", \yii\web\View::POS_READY);

?>

<?php if (isset($_GET['ThreadSearch']['search_form']) && filter_var($_GET['ThreadSearch']['search_form'], FILTER_VALIDATE_BOOLEAN)) : ?>
    <div class="thread-search row" style="margin-bottom: 20px;">
<?php else : ?>
    <div class="thread-search row" style="margin-bottom: 20px; display: none;">
<?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'thread-search',
        'action' => Url::to('index'),
        'method' => 'get',
        'options' => ['data-pjax' => true]
    ]); ?>
    <?= $form->field($model, 'search_form')->hiddenInput(['value' => 1])->label(false); ?>
    <div class="col-md-4">
        <?= $form->field($model, 'subject') ?>

        <div class="form-group">
            <?= Html::tag('label', 'Date range', ['class' => 'control-label']); ?>
            <?= DatePicker::widget([
                'model' => $model,
                'attribute' => 'time_from',
                // 'value' => date('d-m-Y'),
                'type' => DatePicker::TYPE_RANGE,
                'attribute2' => 'time_till',
                // 'value2' => date('d-m-Y'),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]); ?>
        </div>
        <?php echo $form->field($model, 'state')->dropDownList($state_data, ['prompt' => '']) ?>
    </div>

    <div class="col-md-4">
        <?php echo $form->field($model, 'author_id')->widget(Select2::classname(), [
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
        ]) ?>

        <?php echo $form->field($model, 'responsible_id')->widget(Select2::classname(), [
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
        ]) ?>

        <?php echo $form->field($model, 'topic')->widget(Select2::classname(), [
            'data' => array_merge(["" => ""], $topic_data),
            'options' => ['placeholder' => 'Select a topic ...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]); ?>
    </div>

    <div class="col-md-4">
        <?php echo $form->field($model, 'recipient_id')->widget(Select2::classname(), [
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
        ]) ?>
        <?php echo $form->field($model, 'priority')->dropDownList(array_merge(['' => ''], $priority_data)) ?>
        <?php echo $form->field($model, 'watchers')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select watchers ...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'multiple' => true,
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
    </div>


    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'answer_message') ?>


    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>