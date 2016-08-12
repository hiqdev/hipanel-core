<?php

use yii\helpers\Html;

/* @var integer $count */
/* @var array $reminders */
/* @var array $remindInOptions */
/* @var \hipanel\models\Reminder $reminder */
/* @var string $loaderTemplate */

?>
<!-- Notifications Menu -->
<li id="reminders" class="dropdown notifications-menu reminders">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span id="reminder-count"
              class="label label-warning reminder-counts <?= $count > 0 ? '' : 'hidden' ?>"><?= $count ?></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">
            <?= Yii::t('hipanel/reminder', 'Reminders') ?>
        </li>
        <li class="reminder-body">
            <?= $loaderTemplate ?>
        </li>
        <li class="footer"><?= Html::a(Yii::t('hipanel/reminder', 'View all'), ['/reminder/index']) ?></li>

    </ul>
</li>
