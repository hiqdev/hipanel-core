<?php
use cebe\gravatar\Gravatar;
use common\models\File;
use frontend\modules\ticket\models\Thread;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

$this->registerJs(new JsExpression(<<<'JS'
$("#chat-box").find(".scroller").slimScroll({
    height: "50rem",
    allowPageScroll: 1
});
JS
, View::POS_READY));
?>
<!-- Chat box -->
<div class="box box-success">
    <div class="box-header">
        <i class="fa fa-comments-o"></i>
        <h3 class="box-title">Chat</h3>
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
                <?php $model->answers = array_map(function($elem) { if (mb_strlen($elem['message']) > 0) return $elem; }, $model->answers); ?>
                <?php foreach ($model->answers as $answer_id => $answer) : ?>
                    <?= Html::beginTag('li', ['class' => ($answer['is_answer']) ? 'out' : 'in']) ?>
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
                        <?= Html::a($answer['author'], ['/client/client/view', 'id'=>$answer['author_id']], ['class' => 'name']); ?>
                        <?= Html::tag('span', Yii::$app->formatter->asDatetime($answer['create_time']), ['class' => 'datetime'])?>
                        <?= Html::tag('span', Thread::parseMessage($answer['message']), ['class' => 'body']); ?>
                    </div>
                    <?= Html::endTag('li'); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div><!-- /.chat -->

    <div class="box-footer">

    </div>
</div><!-- /.box (chat box) -->