<?php

use yii\helpers\Html;

?>
<?= $this->render('//layouts/gravatar', [
    'email' => Yii::$app->user->identity->email ?? null,
    'size'  => 90,
]) ?>
<?php if (!is_null(Yii::$app->user->identity)) : ?>
<p>
    <b><?= Yii::$app->user->identity->username ?></b>

    <?php if (!Yii::$app->user->isAccountOwner() && isset(Yii::$app->user->identity->seller)) : ?>
        <?= ' / ' . Yii::$app->user->identity->seller ?>
    <?php endif ?>

    <?php if (!Yii::$app->user->is('client')) : ?>
        <small><?= Yii::$app->user->identity->type ?></small>
    <?php else : ?>
        <br>
    <?php endif ?>

    <?php if (Yii::$app->user->identity->name) : ?>
        <?= Html::encode(Yii::$app->user->identity->name) ?>
    <?php endif ?>
</p>
<?php endif ?>
