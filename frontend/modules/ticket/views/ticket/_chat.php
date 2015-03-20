<?php
use cebe\gravatar\Gravatar;
use frontend\modules\ticket\models\Thread;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
//use yii\helpers\Url;
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
<!-- Chat box -->
<div class="box box-success">
    <div class="box-header">
        <i class="fa fa-comments-o"></i>
        <?= Html::tag('h3', Yii::t('app', 'Chat'), ['class' => 'box-title']); ?>
        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
            <!--div class="btn-group" data-toggle="btn-toggle" >
                <button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
            </div-->
        </div>
    </div>

    <div class="box-body chat" id="chat-box">
        <div class="scroller" style="height: 200rem;" data-always-visible="1" data-rail-visible1="1" data-height="200rem">
            <ul class="chats">
                <?php foreach ($model->answers as $answer_id => $answer) : ?>
                    <?php if (ArrayHelper::getValue($answer, 'message') != null) : ?>
                        <?= Html::beginTag('li', ['class' => ($answer['is_answer']) ? 'out' : 'in', 'id' => 'answer-' . $answer['answer_id']]) ?>
                        <?php if (isset($answer['email']) && filter_var($answer['email'], FILTER_VALIDATE_EMAIL)) : ?>
                            <?= Gravatar::widget([
                                'email' => $answer['email'],
                                'defaultImage' => 'identicon',
                                'options' => [
                                    'alt' => $answer['author'],
                                    'class' => 'avatar',
                                ],
                                'size' => 45
                            ]); ?>
                        <?php endif; ?>

                        <div class="message">
                            <span class="arrow"></span>

                            <div class="info">
                                <?= Html::a($answer['author'], [
                                    '/client/client/view',
                                    'id' => $answer['author_id']
                                ], ['class' => 'name']); ?>&nbsp;
                                <?= Html::tag('span', Yii::$app->formatter->asDatetime($answer['create_time']), ['class' => 'datetime']) ?>&nbsp;
                                <?php if ($answer['spent']) print Html::tag('span', Yii::t('app', 'Time spent: {n}', ['n' => $answer['spent']]), ['class' => 'spent-time']); ?>&nbsp;
                            </div>

                            <div class="buttons">
                                <?= Html::button(Yii::t('app', '{i}Quote', ['i' => '<span class="fa fa-quote-left"></span>&nbsp;&nbsp;']), ['class' => 'quote-answer btn btn-xs btn-default', 'data' => ['answer-id' => $answer['answer_id']]]); ?>
                                <?= Html::button(Yii::t('app', '{i}Hide', ['i' => '<span class="fa fa-minus"></span>&nbsp;&nbsp;']), ['class' => 'hide-answer btn btn-xs btn-default', 'data' => ['answer-id' => $answer['answer_id']]]); ?>
                                <?= Html::button(Yii::t('app', '{i}Show', ['i' => '<span class="fa fa-plus"></span>&nbsp;&nbsp;']), ['class' => 'show-answer btn btn-xs btn-default', 'data' => ['answer-id' => $answer['answer_id']], 'style' => 'display: none;']); ?>
                                <? /* = Html::button(Yii::t('app', '{i}Split', ['i' => '<span class="fa fa-scissors"></span>&nbsp;&nbsp;']), ['class' => 'split-answer btn btn-xs btn-default', 'data' => ['answer-id' => $answer['answer_id']]]); */ ?>
                            </div>

                            <div class="clearfix"></div>

                            <?= Html::tag('span', Thread::parseMessage($answer['message']), ['class' => 'body']); ?>
                            <?php if (ArrayHelper::getValue($answer, 'files') != null) : ?>
                                <?= $this->render('_attachment', ['attachment' => $answer['files'], 'object_id' => $model->id, 'object_name' => 'thread', 'answer_id' => $answer_id]); ?>
                            <?php endif; ?>

                        </div>
                        <?= Html::endTag('li'); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div><!-- /.chat -->
</div><!-- /.box (chat box) -->