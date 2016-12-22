<?php

namespace hipanel\menus;

use hiqdev\thememanager\widgets\SidebarMenu as SidebarMenuWidget;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public $widgetConfig = [
        'class' => SidebarMenuWidget::class,
    ];

    public $options = [
        'class' => 'sidebar-menu',
    ];
}
