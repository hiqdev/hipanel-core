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

use hiqdev\thememanager\widgets\UserMenu as UserMenuWidget;
use Yii;

class UserMenu extends \hiqdev\yii2\menus\Menu
{
    public $widgetConfig = [
        'class' => UserMenuWidget::class,
    ];

    public $options = [
        'class' => 'sidebar-menu',
    ];

    public function items()
    {
        return array_filter([
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
            'ticket' => Yii::getAlias('@ticker', false) ? [
                'label' => Yii::t('hipanel', 'Create ticket'),
                'url'   => ['@ticket/create'],
            ] : null,
        ]);
    }
}
