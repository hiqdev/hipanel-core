<?php
use hipanel\widgets\Box;

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel/reminder', 'Reminders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Box::begin() ?>
<?= $this->render('_form', compact(['models', 'model'])); ?>
<?php Box::end() ?>
