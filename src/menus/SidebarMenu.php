<?php

namespace hipanel\menus;

use hipanel\widgets\Menu;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public $widgetConfig = [
        'class' => Menu::class,
    ];

    public $options = [
        'class' => 'sidebar-menu',
    ];
}
