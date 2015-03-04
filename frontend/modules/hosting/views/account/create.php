<?php
/* @var $this yii\web\View */
/* @var $model frontend\modules\ticket\models\Thread */
/* @var $type string */

$title = [
    'user'    => Yii::t('app', 'Create FTP/SSH account'),
    'ftponly' => Yii::t('app', 'Create FTP only account'),
];

$this->title                   = $title[$type];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="account-create">
    <?= $this->render('_form', [
        'model' => $model,
        'type'  => $type,
    ]) ?>
</div>
