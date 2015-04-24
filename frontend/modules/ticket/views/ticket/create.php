<?php
/* @var $this yii\web\View */
use hipanel\widgets\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model frontend\modules\ticket\models\Thread */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ticket',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">
    <?php $form = ActiveForm::begin([
        'action' => $model->scenario == 'insert' ? Url::toRoute(['create']) : Url::toRoute([
            'update',
            'id' => $model->id
        ]),
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'leave-comment-form']
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= Html::a( Yii::t('app', 'Back to index'), ['index'], ['class' => 'btn btn-primary btn-block margin-bottom']); ?>
            <?php $box = Box::begin([
                'options' => [
                    'class' => 'box-solid'
                ],
            ]); ?>
            <?= $this->render('_advanced_form', [
                'form' => $form,
                'model' => $model,
                'topic_data' => $topic_data,
                'priority_data' => $priority_data,
                'state_data' => $state_data,
            ]); ?>
            <?php $box::end(); ?>
        </div>
        <div class="col-md-9">
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
            <?php $box::end(); ?>
            <?php $form::end(); ?>
        </div>
    </div>
</div>
