<?php
/* @var $this yii\web\View */
/* @var $model app\modules\ticket\models\Ticket */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ticket',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ticket-create">

    <?= $this->render('_form', [
        'model' => $model,
        'topic_data' => $topic_data,
        'priority_data' => $priority_data,
        'state_data' => $state_data,
    ]) ?>

</div>
