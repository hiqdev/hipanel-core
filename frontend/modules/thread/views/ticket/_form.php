<?
use yii\helpers\Html;
use frontend\widgets\HiBox;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use frontend\models\Ref;
use yii\helpers\ArrayHelper;
use frontend\components\Re;
$this->registerjs('$("button[data-widget=\'collapse\']").click();', yii\web\View::POS_READY);

?>
<div class="ticket-form">

    <?php $form = ActiveForm::begin([]); ?>

    <? $box = HiBox::begin([
        'title'=>'<i class="fa fa-cogs"></i>&nbsp;&nbsp;Properties',
        'buttonsTemplate'=>'{collapse}',
        'options'=>[]
    ]) ?>
    <!-- Properties -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'topic')->widget(Select2::classname(),[
                'data' => array_merge(["" => ""], ArrayHelper::map(Ref::find()->where(['gtype'=>'topic,ticket'])->getList(),'gl_key', function($o){return Re::l($o->gl_value);})),
                'options' => ['placeholder' => 'Select a topic ...', 'multiple'=>true],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?= $form->field($model, 'state')->widget(Select2::classname(),[
                'data' => array_merge(["" => ""], [
                    'closed' =>"Closed",
                    'opened' =>"Opened",
                    'passed' =>"Passed",
                    'progress' =>"In progress",
                    'waiting' =>"Waiting",
                ]),
                'options' => ['placeholder' => 'Select a state ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
            <?= $form->field($model, 'priority')->widget(Select2::classname(),[
                'data' => array_merge(["" => ""], [
                    'high'      => "High",
                    'medium'    => "Medium",
                ]),
                'options' => ['placeholder' => 'Select a priority ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?
            $url = \yii\helpers\Url::to(['recipients-list']);
            // Script to initialize the selection based on the value of the select2 element
            $initScript = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$url}?id=" + id, {
            dataType: "json"
        }).done(function(data) { callback(data.results);});
    }
}
SCRIPT;
            print $form->field($model, 'responsible')->widget(Select2::classname(), [
                'options' => ['placeholder' => 'Search for a responsible ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => $url,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression($initScript)
                ],
            ]); ?>
            <?= $form->field($model, 'watchers') ?>
            <?= $form->field($model, 'recipient') ?>
        </div>
        <div class="col-md-12"><?= $form->field($model, 'add_tag_ids') ?></div>
    </div>
    <? $box = HiBox::end() ?>


    <?= $form->field($model, 'subject') ?>
    <?= $form->field($model, 'message')->textarea(['rows' => 6]); ?>


    <? $box = HiBox::begin([
        'title'=>'<i class="fa fa-refresh"></i>&nbsp;&nbsp;Preview',
        'buttonsTemplate'=>'{collapse}',
        'options'=>[]
    ]) ?>
        <div class="form-group">
            <label>Preview</label>
            <textarea class="form-control" rows="3" placeholder="Preview ..." disabled=""></textarea>
        </div>
    <? $box = HiBox::end() ?>

    <? $box = HiBox::begin([
        'title'=>'<i class="fa fa-paperclip"></i>&nbsp;&nbsp;Attachments',
        'buttonsTemplate'=>'{collapse}',
        'options'=>[]
    ]) ?>
    <?= $form->field($model, 'file_ids')->widget(\kartik\widgets\FileInput::className(), [
        'options' => ['accept' => 'image/*','multiple' => true],
    ]);?>
    <? $box = HiBox::end() ?>

    <?= $form->field($model, 'spent')->widget(kartik\widgets\TimePicker::className(), [
        'pluginOptions' => [
            'showSeconds' => false,
            'showMeridian' => false,
            'minuteStep' => 1,
            'hourStep' => 1,
        ]
    ]); ?>

    <?//= $form->field($model, 'file_ids') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- ticket-_form -->