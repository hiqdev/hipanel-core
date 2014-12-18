<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
    .text-message {
        margin: 1rem;
    }
');
?>

<p>
    <?= Html::a(Yii::t('app', 'Replay'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Replay'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Close'), ['close', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
</p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'subject',
        [
            'attribute'=>'state',
            'value'=>mb_strtoupper($model->state),
        ],
        [
            'attribute'=>'priority',
            'format'=>'html',
            'value'=>Html::tag('span', $model->priority, ['class'=>'label label-primary']),
        ],
        [
            'attribute'=>'author',
            'format'=>'html',
            'value'=>Html::a($model->author,['/client/client/view','id'=>$model->author_id]),
        ],
        [
            'attribute'=>'responsible',
            'format'=>'html',
            'value'=>Html::a($model->responsible,['/client/client/view','id'=>$model->responsible_id]),
        ],
        [
            'attribute'=>'recipient',
            'format'=>'html',
            'value'=>Html::a($model->recipient,['/client/client/view','id'=>$model->recipient_id]),
        ],
    ],
]); ?>
<!-- Chat box -->
<div class="box box-success">
    <div class="box-header">
        <i class="fa fa-comments-o"></i>
        <h3 class="box-title">Chat</h3>
        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
            <div class="btn-group" data-toggle="btn-toggle" >
                <button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
            </div>
        </div>
    </div>
    <div class="box-body chat" id="chat-box">
<?php foreach ($model->answers as $answer_id => $answer) : ?>
    <!-- chat item -->
    <div class="item <?= ($answer['is_answer']) ? 'move' : ''; ?>" id="answer-<?=$answer['id']?>">
        <?= \cebe\gravatar\Gravatar::widget([
            'email' => $answer['email'],
            'defaultImage' => 'identicon',
            'options' => [
                'alt' => $answer['author'],
            ],
            'size' => 90
        ]); ?>
        <div class="message">
            <?=Html::beginTag('a', ['class'=>'name', 'href'=>Url::toRoute(['/client/default/view', 'id'=>$answer['author_id']]) ]) ?>
                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asDatetime($answer['create_time'])?></small>
                <?= $answer['author'] ?>
            <?= Html::endTag('a'); ?>
            <div class="message-source">
                <?= app\modules\thread\models\Thread::parseMessage($answer['message']) ?>
            </div>
        </div>

        <?php if (!empty($answer['files'])) : ?>
            <div class="attachment">
                <h4>Attachments:</h4>
                <p class="filename">
                    Theme-thumbnail-image.jpg
                </p>
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm btn-flat">Open</button>
                </div>
            </div><!-- /.attachment -->
            <div class="attachments_wrapper">
                <?php foreach ($answer['files'] as $file) : ?>
                    <div class="attachment">
                        <a class="file<?= $file['is_image'] ? ' media' : '' ?>" href="<?= $file['url'] ?>"
                           title="<?= htmlspecialchars($file['filename']) ?>"
                            <? if (!$file['is_image']) { ?>
                                download="<?= htmlspecialchars($file['filename']) ?>"
                            <? } else { ?>
                                target="_blank"
                            <? } ?>
                            >
                            <? if ($file['is_image']) { ?>
                                <img src="<?= $file['url'] ?>" <?= $file['size_string'] ?> wikiparams="<?= $file['wikiparams'] ?>" />
                            <? } else {   ?>
                                <img src="<?= $file['icon'] ?>"/>
                            <? } ?>
                            <span class="name"><?= htmlspecialchars($file['filename']) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div><!-- /.item -->
<?php endforeach; ?>
    </div><!-- /.chat -->
    <div class="box-footer">

    </div>
</div><!-- /.box (chat box) -->
