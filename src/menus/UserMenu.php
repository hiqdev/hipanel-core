<?php

namespace hipanel\menus;

use hiqdev\thememanager\widgets\UserMenu as UserMenuWidget;
use Yii;

class UserMenu extends \hiqdev\menumanager\Menu
{
    public function widget($options = [])
    {
        $defaultOptions = [
            'class' => UserMenuWidget::class,
            'options' => ['class' => 'sidebar-menu'],
        ];

        return parent::widget(array_merge($defaultOptions, $options));
    }

    public function items()
    {
        return [
            'header' => [
                'label' => $this->render('userMenuHeader'),
            ],
            'profile' => [
                'label' => Yii::t('hipanel', 'Profile'),
                'url'   => ['/site/profile'],
            ],
            'logout' => [
                'label' => Yii::t('hipanel', 'Sign out'),
                'url'   => ['/site/logout'],
            ],

            'deposit' => [
                'label' => Yii::t('hipanel', 'Recharge account'),
                'url'   => ['@pay/deposit'],
                'visible' => Yii::$app->user->can('deposit'),
            ],
            'ticket' => [
                'label' => Yii::t('hipanel', 'Create ticket'),
                'url'   => ['@ticket/create'],
            ],
        ];
    }
}
