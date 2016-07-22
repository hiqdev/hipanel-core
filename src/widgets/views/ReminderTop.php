<?php
use yii\helpers\Html;
/* @var integer $count */
/* @var array $reminders */
/* @var \hipanel\models\Reminder $reminder */
?>
<!-- Notifications Menu -->
<li class="dropdown notifications-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <?php if ($count > 0) : ?>
            <span class="label label-warning"><?= $count ?></span>
        <?php endif ?>
    </a>
    <ul class="dropdown-menu">
        <li class="header"><?= Yii::t('hipanel', 'You have {0} notifications', [$count]) ?></li>
        <li>
            <ul class="menu">
                <?php foreach ($reminders as $reminder) : ?>
                    <li>
                        <a href="#">
                            <h4>
                                Support Team
                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                            </h4>
                            <p>Why not buy a new awesome theme?</p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="footer"><?= Html::a(Yii::t('hipanel', 'View all'), ['/reminder/index']) ?></li>

    </ul>
</li>
