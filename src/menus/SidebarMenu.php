<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

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
