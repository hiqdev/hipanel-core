<?php

use yii\helpers\Html;

/** @var string $subTitle */
/** @var string $menu */
/** @var string $icon */
/** @var string $image */
/** @var boolean $background */
?>
<style>
    .skin-blue .widget-user-2 .widget-user-header {
        background-color: #3c8dbc;
    }

    .skin-red .widget-user-2 .widget-user-header {
        background-color: #dd4b39;
    }

    .skin-green .widget-user-2 .widget-user-header {
        background-color: #00a65a;
    }

    .skin-purple .widget-user-2 .widget-user-header {
        background-color: #605ca8;
    }

    .skin-yellow .widget-user-2 .widget-user-header {
        background-color: #f39c12;
    }

    #bg-image {
        height: 100px;
        background-image: url('http://mini.s-shot.ru/1920x1200/JPEG/260/Z100/?http://www.cish.org/');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: 100% 0;
    }

    .widget-user-username,
    .widget-user-username a,
    .widget-user-username a:focus,
    .widget-user-username a:visited,
    .widget-user-desc,
    .widget-user-desc a,
    .widget-user-desc a:focus,
    .widget-user-desc a:visited {
        color: #fff;
    }

    .widget-user-username a:hover,
    .widget-user-desc a:hover {
        text-decoration: underline;
    }

    .box-menu > ul > li {
        border-bottom: 1px solid #f0f4f7;
    }

    .box-menu > ul > li:last-child {
        border-bottom: none;
    }
</style>

<div class="box box-widget widget-user-2">
    <div class="widget-user-header"
         <?php if ($backgroundColor) : ?>style="background-color: <?= $backgroundColor ?> !important;"<?php endif; ?>>
        <div class="widget-user-image">
            <?php if ($image && $background === false) : ?>
                <?= $image ?>
            <?php else : ?>
                <?= Html::tag('i', '', ['class' => sprintf('fa %s fa-4x pull-left fa-inverse', $icon)]) ?>
            <?php endif; ?>
        </div>
        <?= Html::tag('h4', $title, array_merge(['class' => 'widget-user-username'], $titleOptions)) ?>
        <h5 class="widget-user-desc"><?= $subTitle ?></h5>
    </div>
    <?php if ($background) : ?>
        <div id="bg-image"></div>
    <?php endif; ?>
    <div class="box-footer no-padding box-menu">
        <?= $menu ?>
    </div>
</div>
