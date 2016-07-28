<?php
use hipanel\helpers\StringHelper;
use hipanel\helpers\Url;
use yii\helpers\Html;

/* @var integer $count */
/* @var array $reminders */
/* @var array $remindInOptions */
/* @var \hipanel\models\Reminder $reminder */

$reminderUpdateLink = Url::toRoute(['@reminder/update']);
$reminderDeleteLink = Url::toRoute(['@reminder/delete']);
$this->registerJs(<<<"JS"
JS
);

$this->registerCss(<<<CSS
 
CSS

);
?>
<!-- Notifications Menu -->
<li id="reminders" class="dropdown notifications-menu reminders">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <?php if ($count > 0) : ?>
            <span id="reminder-count" class="label label-warning reminder-counts"><?= $count ?></span>
        <?php endif ?>
    </a>
    <ul class="dropdown-menu">
        <li class="header"><?= Yii::t('hipanel', 'You have {0} notifications', [Html::tag('span', $count, ['class' => 'reminder-counts'])]) ?></li>
        <li>
            <ul class="menu">

            </ul>
        </li>
        <li class="footer"><?= Html::a(Yii::t('hipanel/reminder', 'View all'), ['/reminder/index']) ?></li>

    </ul>
</li>
