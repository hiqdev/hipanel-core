<?php

namespace hipanel\menus;

class UserMenu extends \hiqdev\menumanager\Menu
{
    public function render($options = [])
    {
        $defaultOptions = [
            'class' => 'UserMenu',
            'options' => ['class' => 'sidebar-menu'],
        ];

        return parent::render(array_merge($defaultOptions, $options));
    }

    public function items()
    {
        return [
            'header' => [
                'label' => $this->renderView('userMenuHeader'),
            ],
            'body' => [
                'label' => $this->renderView('userMenuBody'),
            ],
        ];
    }
}
