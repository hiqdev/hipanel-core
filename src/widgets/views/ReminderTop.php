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
$( ".reminder-actions" ).click(function(e) {
    e.preventDefault();
    var ajaxLink, ajaxData = {};
    var reminder = $(this); 
    var id = reminder.data('reminder-id'); 
    var action = reminder.data('reminder-action');
    var reminderNode = $('#reminder-' + id); 
    var reminderCount = $('#reminder-count');
    var reminderCounts = $('.reminder-counts');
    if (action == 'delete') {
        ajaxLink = '{$reminderDeleteLink}';
        ajaxData = {'Reminder': {'id': id}};
    } else {
        ajaxLink = '{$reminderUpdateLink}';
        ajaxData = {
               'Reminder': {
                    'id': id,   
                    'next_time': action
               }  
        }; 
    }
    // Ajax reminder request
    $.ajax({
        'type': 'POST', 
        'url': ajaxLink, 
        'data': ajaxData 
    }).done(function() {
        var count = reminderCount - 1;
        // Update count
        reminderCounts.html(count == 0 ? '0' : count);
        // Hide Reminder node
        reminderNode.slideUp();
    });     
    
    return false;
});
JS
);

$this->registerCss(<<<CSS
 
.navbar-nav>.reminders>.dropdown-menu>li .menu>li>a {
    margin: 0;
    padding: 7px;
}

.navbar-nav>.reminders>.dropdown-menu>li .menu>li>a>h4 {
    padding-top: 1.2em;
    margin-top: 5px;
    color: #444444;
    font-size: 15px;
    position: relative
}

.navbar-nav>.reminders>.dropdown-menu>li .menu>li>a>h4>small {
    color: #999999;
    font-size: 10px;
    position: absolute;
    top: 0;
    right: 0
}

.navbar-nav>.reminders>.dropdown-menu>li .menu>li>a>p {
    font-size: 12px;
    color: #888888;
    margin-bottom: 5px;
}

.navbar-nav>.reminders>.dropdown-menu>li .menu>li>a:before,
.navbar-nav>.reminders>.dropdown-menu>li .menu>li>a:after {
    content: " ";
    display: table
}

.navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a:after {
    clear: both
}
CSS

);
?>
<!-- Notifications Menu -->
<li class="dropdown notifications-menu reminders">
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
                <?php foreach ($reminders as $reminder) : ?>
                    <li id="reminder-<?= $reminder->id ?>">
                        <?= Html::beginTag('a', ['href' => Url::toRoute([sprintf("@%s/view", $reminder->objectName), 'id' => $reminder->object_id])]) ?>
                        <h4>
                            <?= Yii::t('hipanel/reminder', "{0} ID #{1}", [Yii::t('hipanel/reminder', ucfirst($reminder->objectName)), $reminder->object_id]) ?>
                            <small><?= Yii::t('hipanel/reminder', 'Next time') ?>: <?= $reminder->next_time ?></small>
                        </h4>
                        <p>
                            <?= StringHelper::truncateWords(Html::encode($reminder->message), 3) ?>
                        </p>
                        <small>
                            <?= Yii::t('hipanel/reminder', 'Remind in') ?>:
                            <?php foreach ($remindInOptions as $time => $label) : ?>
                                <?= Html::button(
                                    Yii::t('hipanel/reminder', $label),
                                    [
                                        'class' => 'btn btn-xs btn-link reminder-actions',
                                        'data' => [
                                            'reminder-id' => $reminder->id,
                                            'reminder-action' => $time,
                                        ],
                                    ]
                                ) ?>
                            <?php endforeach ?>
                            <br>
                            <?= Html::button(Yii::t('hipanel/reminder', 'Don\'t remind'), [
                                'class' => 'btn btn-xs btn-block btn-danger reminder-actions lg-mt-10 md-mt-10',
                                'data' => [
                                    'reminder-id' => $reminder->id,
                                    'reminder-action' => 'delete',
                                ],
                            ]) ?>
                        </small>
                        <?= Html::endTag('a') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="footer"><?= Html::a(Yii::t('hipanel/reminder', 'View all'), ['/reminder/index']) ?></li>

    </ul>
</li>
