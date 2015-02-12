<?php
use frontend\components\Re;
use frontend\modules\thread\widgets\Label;
use frontend\modules\thread\widgets\Topic;
use frontend\modules\thread\widgets\Watcher;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\StringHelper;
$this->title = StringHelper::truncateWords($model->threadViewTitle, 5);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
    .text-message {
        margin: 1rem;
    }
');
?>

<p>
    <?= Html::a('<i class="fa fa-reply"></i>&nbsp;&nbsp;'.Yii::t('app', 'Replay'), '#', ['class'=>'btn btn-default', 'onClick' => new \yii\web\JsExpression('
    $("html, body").animate({
        scrollTop: $(".ticket-update").offset().top
    }, 3000);
    ')]) ?>

    <?php if (is_array($model->watcher) && in_array(Yii::$app->user->identity->username, $model->watcher)) : ?>
        <?= Html::a('<i class="fa fa-pencil"></i>&nbsp;&nbsp;'.Yii::t('app', 'Unsubscribe'), ['unsubscribe', 'id' => $model->id], ['class' => 'btn  btn-default']) ?>
    <?php else : ?>
        <?= Html::a('<i class="fa fa-pencil"></i>&nbsp;&nbsp;'.Yii::t('app', 'Subscribe'), ['subscribe', 'id' => $model->id], ['class' => 'btn  btn-default']) ?>
    <?php endif; ?>

    <?php if ($model->state=='opened') : ?>
        <?= Html::a('<i class="fa fa-close"></i>&nbsp;&nbsp;'.Yii::t('app', 'Close'), ['close', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to close this ticket?'),
                'method' => 'post',
            ],
        ]) ?>
    <?php else : ?>
        <?= Html::a('<i class="fa fa-close"></i>&nbsp;&nbsp;'.Yii::t('app', 'Open'), ['open', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to open this ticket?'),
                'method' => 'post',
            ],
        ]) ?>
    <?php endif; ?>

    <?php if ($model->priority == 'medium') : ?>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-up"></span>&nbsp;&nbsp;'.Yii::t('app', 'Increase'), ['priority-up', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    <?php else : ?>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-down"></span>&nbsp;&nbsp;'.Yii::t('app', 'Lower'), ['priority-down', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    <?php endif; ?>
</p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'subject',
        [
            'attribute'=>'state',
            'format'=>'html',
            'value'=>Label::widget([
                    'type'=>'state',
                    'label'=> Re::l($model->state_label),
                    'value'=>$model->state,
                ]),
        ],
        [
            'attribute' => 'topic',
            'format'=>'html',
            'value' => Topic::widget(['topic' => $model->topic]),
            'visible' => $model->topic != null,
        ],
        [
            'attribute'=>'priority',
            'format'=>'html',
            'value'=> Label::widget([
                    'type'=>'priority',
                    'label'=> Re::l($model->priority_label),
                    'value'=>$model->priority,
                ])
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
            'visible'=> $model->responsible != null,
        ],
        [
            'attribute'=>'recipient',
            'format'=>'html',
            'value'=>Html::a($model->recipient,['/client/client/view','id'=>$model->recipient_id]),
        ],
        [
            'attribute'=>'watcher',
            'format'=>'html',
            'value'=> Watcher::widget(['watchers'=>$model->watcher]),
            'visible'=> is_array($model->watcher)
        ],
    ],
]); ?>
<?php if (is_array($model->answers)) : ?>
    <?= $this->render('_chat',['model'=>$model]); ?>
<?php endif; ?>

<div class="ticket-update">

    <?= $this->render('_form', [
        'model' => $model,
        'topic_data' => $topic_data,
        'priority_data' => $priority_data,
        'state_data' => $state_data,
    ]) ?>

</div>

