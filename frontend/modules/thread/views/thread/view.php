<?php
use frontend\components\Re;
use frontend\modules\thread\widgets\Label;
use frontend\modules\thread\widgets\Watcher;
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
//\yii\helpers\VarDumper::dump(Yii::$app->user->identity->username, 10, true);
?>

<p>
    <?= Html::a('<i class="fa fa-reply"></i>&nbsp;&nbsp;'.Yii::t('app', 'Replay'), '#', ['class'=>'btn btn-default', 'onClick' => new \yii\web\JsExpression('return false;')]) ?>

    <?php if (is_array($model->watcher) && in_array(Yii::$app->user->identity->username, $model->watcher)) : ?>
        <?= Html::a('<i class="fa fa-pencil"></i>&nbsp;&nbsp;'.Yii::t('app', 'Unsubscribe'), ['unsubscribe', 'id' => $model->id], ['class' => 'btn  btn-default']) ?>
    <?php else : ?>
        <?= Html::a('<i class="fa fa-pencil"></i>&nbsp;&nbsp;'.Yii::t('app', 'Subscribe'), ['subscribe', 'id' => $model->id], ['class' => 'btn  btn-default']) ?>
    <?php endif; ?>

    <?= Html::a('<i class="fa fa-close"></i>&nbsp;&nbsp;'.Yii::t('app', 'Close'), ['close', 'id' => $model->id], [
        'class' => 'btn btn-danger pull-right',
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
            'format'=>'html',
            'value'=>Label::widget([
                    'type'=>'state',
                    'label'=> Re::l($model->state_label),
                    'value'=>$model->state,
                ]),
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

