<?php
use yii\helpers\Html;
?>
<!-- Notifications Menu -->
<li class="dropdown notifications-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning">1</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header"><?= Yii::t('hipanel', 'You have {0} notifications', [1]) ?></li>
        <li>
            <!-- Inner Menu: contains the notifications -->
            <ul class="menu">
                <li><!-- start notification -->
                    <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                </li>
                <!-- end notification -->
            </ul>
        </li>
        <li class="footer"><?= Html::a(Yii::t('hipanel', 'View all'), ['/reminder/index']) ?></li>

    </ul>
</li>
