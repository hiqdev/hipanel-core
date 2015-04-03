<?php
/* @var $this yii\web\View */
/* @var $model frontend\modules\ticket\models\Thread */
/* @var $type string */

$this->title                   = $title[$type];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Databases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="account-create">
    <?= $this->render('_form', compact('model')) ?>
</div>
