<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */
use cebe\gravatar\Gravatar;
use frontend\modules\ticket\models\Thread;
use hipanel\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<?= Html::beginTag('div', ['class' => ($answer['is_answer']) ? 'comment answer' : 'comment', 'id' => 'answer-' . $answer['answer_id']]); ?>
    <!-- Avatar -->
<?php if (isset($answer['email']) && filter_var($answer['email'], FILTER_VALIDATE_EMAIL)) : ?>
    <?= Gravatar::widget([
        'email' => $answer['email'],
        'defaultImage' => 'identicon',
        'options' => [
            'alt' => $answer['author'],
            'class' => 'comment-avatar',
        ],
        'size' => 32
    ]); ?>
<?php endif; ?>

<?= Html::beginTag('div', ['class' => 'comment-body']); ?>
<?= Html::beginTag('div', ['class' => 'comment-text']); ?>
    <div class="comment-heading">
        <?= Html::a($answer['author'], [
            '/client/client/view',
            'id' => $answer['author_id']
        ], ['class' => 'name']); ?><?= Html::tag('span', Yii::$app->formatter->asDatetime($answer['create_time'])) ?>
    </div>

<?= Html::tag('span', Thread::parseMessage($answer['message']), ['class' => 'body']); ?>

<?php if (ArrayHelper::getValue($answer, 'files') != null) : ?>
    <?= $this->render('_attachment', ['attachment' => $answer['files'], 'object_id' => $model->id, 'object_name' => 'thread', 'answer_id' => $answer_id]); ?>
<?php endif; ?>

<?= Html::endTag('div'); ?>

<?= Html::beginTag('div', ['class' => 'comment-footer']); ?>
    <a href="#">Reply</a>
    &nbsp;&nbsp;·&nbsp;&nbsp;
    <a href="#">Qute</a>
<?= Html::endTag('div'); ?>

<?= Html::endTag('div'); ?>

<?= Html::endTag('div'); ?>