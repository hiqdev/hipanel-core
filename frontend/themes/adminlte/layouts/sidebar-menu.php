<?php

/**
 * Sidebar menu layout.
 *
 * @var \yii\web\View $this View
 */

use frontend\components\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
    ]
);
