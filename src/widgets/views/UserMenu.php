<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <?= $this->render('//layouts/gravatar', ['class' => 'user-image']) ?>
    <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
</a>
<?= $this->render('//layouts/user-menu') ?>
