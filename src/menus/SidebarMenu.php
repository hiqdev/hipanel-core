<?php

namespace hipanel\menus;

class SidebarMenu extends \hiqdev\menumanager\Menu
{
    public function render($options = [])
    {
        $hipanelOptions = [
            'class' => \hipanel\widgets\Menu::class,
            'options' => ['class' => 'sidebar-menu'],
        ];

        return parent::render(array_merge($hipanelOptions, $options));
    }
}
