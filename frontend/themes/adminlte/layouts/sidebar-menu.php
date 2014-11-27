<?php

/**
 * Sidebar menu layout.
 *
 * @var \yii\web\View $this View
 */

use frontend\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => 'Dashboard',
                'url' => Yii::$app->homeUrl,
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl
            ],
            [
                'label' => Yii::t('app', 'Clients'),
                'url' => ['/client/default/index'],
                'icon' => 'fa-group',
                // 'visible' => Yii::$app->user->can('administrateUsers') || Yii::$app->user->can('BViewUsers'),
            ],
            [
                'label' => Yii::t('app', 'Tickets'),
                'url' => ['/ticket/default/index'],
                'icon' => 'fa-folder',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Tickets'),
                        'url' => ['/ticket/default/index'],
                    ],
                    [
                        'label' => Yii::t('app', 'Tickets settings'),
                        'url' => ['/ticket/default/settings'],
                    ],
                    [
                        'label' => Yii::t('app', 'Tickets templates'),
                        'url' => ['/ticket/template/index'],
                    ],
                    [
                        'label' => Yii::t('app', 'Ticket tags'),
                        'url' => ['/ticket/tag/index'],
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Domains'),
                'url' => ['/domains/default/index'],
                'icon' => 'fa-folder',
                'items' => [
                    [
                        'label' => Yii::t('app', 'My domains'),
                        'url' => ['/domain/default/mydomains'],
                    ],
                    [
                        'label' => Yii::t('app', 'Nameservers'),
                        'url' => ['/domain/nameservers/index'],
                    ],
                    [
                        'label' => Yii::t('app', 'Contacts'),
                        'url' => ['/domain/contacts/index'],
                    ],
                    [
                        'label' => Yii::t('app', 'SEO'),
                        'url' => ['/domain/default/seo'],
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Access control'),
                'url' => '#',
                'icon' => 'fa-gavel',
                // 'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles') || Yii::$app->user->can('BViewPermissions') || Yii::$app->user->can('BViewRules'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Permissions'),
                        'url' => ['/rbac/permissions/index'],
                   //     'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewPermissions')
                    ],
                    [
                        'label' => Yii::t('app', 'Roles'),
                        'url' => ['/rbac/roles/index'],
//                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles')
                    ],
                    [
                        'label' => Yii::t('app', 'Rules'),
                        'url' => ['/rbac/rules/index'],
//                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRules')
                    ]
                ]
            ],
        ]
    ]
);