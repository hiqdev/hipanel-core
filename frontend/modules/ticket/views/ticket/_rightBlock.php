<?php
/**
 * @link http://hiqdev.com/...
 * @copyright Copyright (c) 2015 HiQDev
 * @license http://hiqdev.com/.../license
 */

use hipanel\widgets\Box;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

$this->registerJs(new JsExpression(<<<'JS'
$("#chat-box").find(".scroller").slimScroll({
    height: "50rem",
    allowPageScroll: 1,
    start: 'bottom'
});
// Handle Quote
$(".chats").on("click", ".quote-answer", function() {
    var answer_id = $(this).data('answer-id');
    var overlay = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
    var formBox = $('#thread-message').parents('.box');
    formBox.append(overlay); // Add ajax spiner
    $('#ticket-reply-button').click(); // Scroll to form
    $('#thread-message').load('/ticket/ticket/get-quoted-answer', {id: answer_id}, function() {
        formBox.find('.overlay').remove(); // Remove ajax spiner
    });

});
// Handle Hide
$(".chats").on("click", ".hide-answer", function() {
    var answer_id = $(this).data('answer-id');
    console.log(answer_id);
    $(this).parents('.message').find('.body, .attachment, .hide-answer, .show-answer').toggle('slow');
});
// Handle Show
$(".chats").on("click", ".show-answer", function() {
    var answer_id = $(this).data('answer-id');
    console.log(answer_id);
    $(this).parents('.message').find('.body, .attachment, .hide-answer, .show-answer').toggle('slow');
});
// TODO: Handle Split
JS
    , View::POS_READY));
?>

<?php if (is_array($model->answers)) : ?>
    <!-- Chat box -->
    <?php $box = Box::begin([
        'options' => [
            'class' => 'box-primary'
        ]
    ]); ?>
    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'topic_data' => $topic_data,
        'priority_data' => $priority_data,
        'state_data' => $state_data,
    ]) ?>
    <hr class="no-panel-padding-h panel-wide padding-bottom">
        <div class="widget-article-comments tab-pane panel no-padding no-border fade in active">
            <?php foreach ($model->answers as $answer_id => $answer) : ?>
                <?php if (ArrayHelper::getValue($answer, 'message') != null) : ?>
                    <?= $this->render('_comment', ['model' => $model, 'answer_id' => $answer_id, 'answer' => $answer]); ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php $box::end(); ?><!-- /.box (chat box) -->
        </div>
<?php endif; ?>