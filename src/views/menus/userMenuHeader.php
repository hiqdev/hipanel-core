<?= $this->render('//layouts/gravatar', [
    'email' => Yii::$app->user->identity->email,
    'size'  => 90,
]) ?>
<p>
    <b><?= Yii::$app->user->identity->username ?></b>

    <?php if (Yii::$app->user->can('support') && isset(Yii::$app->user->identity->seller)) : ?>
        <?= ' / ' . Yii::$app->user->identity->seller ?>
    <?php endif ?>

    <?php if (Yii::$app->user->can('support')) : ?>
        <small><?= Yii::$app->user->identity->type ?></small>
    <?php else : ?>
        <br>
    <?php endif ?>

    <?php if (Yii::$app->user->identity->name) : ?>
        <?= Yii::$app->user->identity->name ?>
    <?php endif ?>
</p>
