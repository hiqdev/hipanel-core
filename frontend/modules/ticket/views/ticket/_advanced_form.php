<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */
use hipanel\widgets\Combo2;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\Select2;

?>
    <!-- Topics -->
<?php if ($model->isNewRecord)
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
<div class="row">
    <div class="col-md-6">
        <!-- State -->
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
    </div>
    <div class="col-md-6">
        <!-- Priority -->
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
    </div>
</div>

    <!-- Responsible -->
<?= $form->field($model, 'responsible_id')->widget(Combo2::classname(), [
    'type' => \hipanel\modules\client\assets\combo2\Manager::className()
//    'options' => ['placeholder' => 'Search for a responsible ...'],
//    'pluginOptions' => [
//        'allowClear' => true,
//        'minimumInputLength' => 3,
//        'ajax' => [
//            'url' => Url::to(['/client/client/can-manage-list']),
//            'dataType' => 'json',
//            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
//            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
//        ],
//        'initSelection' => new JsExpression('function (elem, callback) {
//                                                            var id=$(elem).val();
//                                                            $.ajax("' . Url::to(['/client/client/can-manage-list']) . '?id=" + id, {
//                                                                dataType: "json"
//                                                            }).done(function(data) {
//                                                                callback(data.results);
//                                                            });
//                                                        }')
//    ],
]); ?>

<?php if ($model->scenario == 'insert') : ?>
    <?= $form->field($model, 'watchers')->widget(Combo2::classname(), [
        'type' => 'client'
//        'options' => ['placeholder' => 'Select watchers ...', 'multiple' => true],
//        'pluginOptions' => [
//            'allowClear' => true,
//            'minimumInputLength' => 3,
//            'multiple' => true,
//            'ajax' => [
//                'url' => Url::to(['/client/client/can-manage-list']),
//                'dataType' => 'json',
//                'data' => new JsExpression('function(term,page) { return {search:term}; }'),
//                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
//            ],
//            'initSelection' => new JsExpression('function (elem, callback) {
//                                                            var id=$(elem).val();
//                                                            $.ajax("' . Url::to(['/client/client/can-manage-list']) . '?id=" + id, {
//                                                                dataType: "json"
//                                                            }).done(function(data) {
//                                                                callback(data.results);
//                                                            });
//                                                        }')
//        ],
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