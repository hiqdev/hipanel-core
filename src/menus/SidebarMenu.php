<?php

namespace hipanel\menus;

class SidebarMenu extends \hiqdev\menumanager\Menu
{
    public function widget($options = [])
    {
        $hipanelOptions = [
            'class' => \hipanel\widgets\Menu::class,
            'options' => ['class' => 'sidebar-menu'],
        ];

        return parent::widget(array_merge($hipanelOptions, $options));
    }
}
