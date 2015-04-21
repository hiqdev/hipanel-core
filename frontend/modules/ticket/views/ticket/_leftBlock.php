<?php
/**
 * @link http://hiqdev.com/...
 * @copyright Copyright (c) 2015 HiQDev
 * @license http://hiqdev.com/.../license
 */
use hipanel\widgets\Box;
use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\modules\ticket\widgets\Label;
use frontend\modules\ticket\widgets\Topic;
use frontend\modules\ticket\widgets\Watcher;
use hipanel\base\Re;

//\yii\helpers\VarDumper::dump($model, 10, true);
?>
<div class="row page-ticket">

    <div class="col-md-12 profile-block">
        <div class="panel profile-photo">
            <?= \cebe\gravatar\Gravatar::widget([
                'email' => $model->email,
                'defaultImage' => 'identicon',
                'options' => [
                    'alt' => '',
                    'class' => 'img-circle',
                ],
                'size' => 160,
            ]); ?>
        </div>

    </div>

    <div class="col-md-12 text-center">
        <?php if (is_array($model->watcher) && in_array(Yii::$app->user->identity->username, $model->watcher)) : ?>
            <?= Html::a('<i class="fa fa-eye-slash"></i>&nbsp;&nbsp;'.Yii::t('app', 'Unsubscribe'), ['unsubscribe', 'id' => $model->id], ['class' => 'btn  btn-default']) ?>
        <?php else : ?>
            <?= Html::a('<i class="fa fa-eye"></i>&nbsp;&nbsp;'.Yii::t('app', 'Subscribe'), ['subscribe', 'id' => $model->id], ['class' => 'btn  btn-default']) ?>
        <?php endif; ?>
        &nbsp;
        <?php if ($model->priority == 'medium') : ?>
            <?= Html::a('<span class="glyphicon glyphicon-arrow-up"></span>&nbsp;&nbsp;'.Yii::t('app', 'Increase'), ['priority-up', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?php else : ?>
            <?= Html::a('<span class="glyphicon glyphicon-arrow-down"></span>&nbsp;&nbsp;'.Yii::t('app', 'Lower'), ['priority-down', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?php endif; ?>
        &nbsp;
        <?php if ($model->state=='opened') : ?>
            <?= Html::a('<i class="fa fa-close"></i>&nbsp;&nbsp;'.Yii::t('app', 'Close'), ['close', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to close this ticket?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php else : ?>
            <?= Html::a('<i class="fa fa-close"></i>&nbsp;&nbsp;'.Yii::t('app', 'Open'), ['open', 'id' => $model->id], [
                'class' => 'btn btn-danger ',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to open this ticket?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </div>

    <div class="col-md-12">
        <div class="panel panel-transparent">
            <!--div class="panel-heading">
                <span class="panel-title">About me</span>
            </div-->
            <div class="panel-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        // 'subject',
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
                            'attribute' => 'topics',
                            'format'=>'html',
                            'value' => Topic::widget(['topics' => $model->topics]),
                            'visible' => $model->topics != null,
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
            </div>
        </div>
    </div>
</div>