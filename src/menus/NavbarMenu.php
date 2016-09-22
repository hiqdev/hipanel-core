<?php

namespace hipanel\menus;

use hipanel\widgets\UserMenu;
use hiqdev\menumanager\Menu;
use hiqdev\yii2\cart\widgets\PanelTopCart;
use hiqdev\yii2\language\widgets\LanguageMenu;
use hiqdev\yii2\reminder\widgets\ReminderTop;

class NavbarMenu extends Menu
{
    public function items()
    {
        return [
            [
                'label' => LanguageMenu::widget(),
                'encode' => false,
                'options' => [
                    'class' => 'dropdown language-menu',
                ],
            ],
            [
                'label' => ReminderTop::widget(),
                'encode' => false,
                'options' => [
                    'id' => 'reminders',
                    'class' => 'dropdown notifications-menu reminders',
                ],
            ],
            [
                'label' => PanelTopCart::widget(),
                'encode' => false,
                'options' => [
                    'class' => 'dropdown notifications-menu'
                ],
            ],
            [
                'label' => UserMenu::widget(),
                'encode' => false,
                'options' => [
                    'class' => 'dropdown user user-menu'
                ]
            ],
            [
                'label' => '<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>',
                'encode' => false,
            ]
        ];
    }
}
