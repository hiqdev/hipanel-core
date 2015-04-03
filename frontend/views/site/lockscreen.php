<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 01.04.15
 * Time: 17:53
 */
use yii\helpers\Html;

$this->blocks['bodyClass'] = 'lockscreen';
$this->title = 'Lockscreen';
?>

<!-- User name -->
<div class="lockscreen-name"><?= \Yii::$app->user->identity->username ?></div>

<!-- START LOCK SCREEN ITEM -->
<div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
        <?= $this->render('/layouts/gravatar',['size'=>128]) ?>
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials">
        <input type="submit" class="form-control btn" value="Return to HiPanel" />
    </form><!-- /.lockscreen credentials -->

</div><!-- /.lockscreen-item -->
<!-- div class="help-block text-center">
    Enter your password to retrieve your session
</div -->
<div class='text-center'>
    <b><?= Html::a('Or logout and sign in as a different user', ['/site/logout']); ?></b>
</div>
<div class='lockscreen-footer text-center'>
    Copyright &copy; 2014-2015 <b><a href="http://hipanel.com" class='text-black'><?= Yii::$app->name; ?></a></b><br>
    All rights reserved
</div>
