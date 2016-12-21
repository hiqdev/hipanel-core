<?php

namespace hipanel\menus;

use hipanel\widgets\Menu;

class SidebarMenu extends \hiqdev\menumanager\Menu
{
    public $widgetConfig = [
        'class' => Menu::class,
    ];

    public $options = [
        'class' => 'sidebar-menu',
    ];
}
