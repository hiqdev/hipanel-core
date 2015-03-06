<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 03.03.15
 * Time: 18:13
 */

use frontend\widgets\Menu;
use Yii;
?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= $this->render('gravatar'); ?>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <?= Menu::widget(
            [
                'options' => [
                    'class' => 'sidebar-menu'
                ],
                'items' => [
                    [
                        'label' => Yii::t('app', 'MAIN NAVIGATION'),
                        'options' => ['class' => 'header'],
                    ],
                    [
                        'label' => 'Dashboard',
                        'url' => ['/hipanel'],
                        'icon' => 'fa-dashboard',
                        'active' => Yii::$app->request->url === Yii::$app->homeUrl
                    ],
                    [
                        'label' => Yii::t('app', 'Clients'),
                        'url' => ['/client/client/index'],
                        'icon' => 'fa-group',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Clients list'),
                                'url' => ['/client/client/index'],
                                'icon' => 'fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Mailings'),
                                'url' => ['/client/mailing/index'],
                                'icon' => 'fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'News and articles'),
                                'url' => ['/client/article/index'],
                                'icon' => 'fa-circle-o',
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Tickets'),
                        'url' => ['/ticket/ticket/index'],
                        'icon' => 'fa-ticket',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Tickets'),
                                'url' => ['/ticket/ticket/index'],
                                'icon' => 'fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Tickets settings'),
                                'url' => ['/ticket/ticket/settings'],
                                'icon' => 'fa-circle-o',
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Domains'),
                        'url' => ['/domains/default/index'],
                        'icon' => 'fa-globe',
                        'visible' => true,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'My domains'),
                                'url' => ['/domain/domain/index'],
                                'icon' => 'fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Name Servers'),
                                'url' => ['/domain/nameservers/index'],
                                'icon' => 'fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Contacts'),
                                'url' => ['/client/contacts/index'],
                                'icon' => 'fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'SEO'),
                                'url' => ['/domain/domain/index'],
                                'icon' => 'fa-circle-o',
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Servers'),
                        'url' => ['/server/server/index'],
                        'icon' => 'fa-server',
                        'visible' => true,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Servers'),
                                'url' => ['/server/server/index'],
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Hosting'),
                        'url' => '#',
                        'icon' => 'fa-sitemap',
                        'visible' => true,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Accounts'),
                                'url' => ['/hosting/account/index'],
                                'icon' => 'fa-circle-o',
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Access control'),
                        'url' => '#',
                        'icon' => 'fa-gavel',
                        'visible' => false,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Permissions'),
                                'url' => ['/rbac/permissions/index'],
                                'icon' => 'fa-circle-o',
                                //     'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewPermissions')
                            ],
                            [
                                'label' => Yii::t('app', 'Roles'),
                                'url' => ['/rbac/roles/index'],
                                'icon' => 'fa-circle-o',
                                //                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles')
                            ],
                            [
                                'label' => Yii::t('app', 'Rules'),
                                'url' => ['/rbac/rules/index'],
                                'icon' => 'fa-circle-o',
                                //                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRules')
                            ]
                        ]
                    ],
                ]
            ]
        ); ?>
        <!--ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../../index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                    <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
                </ul>
            </li>
            <li class="treeview active">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Layout Options</span>
                    <span class="label label-primary pull-right">4</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                    <li><a href="../layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                    <li class="active"><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                    <li><a href="collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                </ul>
            </li>
            <li>
                <a href="../widgets.html">
                    <i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Charts</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                    <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                    <li><a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>UI Elements</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                    <li><a href="../UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                    <li><a href="../UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                    <li><a href="../UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                    <li><a href="../UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                    <li><a href="../UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>Forms</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
                    <li><a href="../forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
                    <li><a href="../forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-table"></i> <span>Tables</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                    <li><a href="../tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
                </ul>
            </li>
            <li>
                <a href="../calendar.html">
                    <i class="fa fa-calendar"></i> <span>Calendar</span>
                    <small class="label pull-right bg-red">3</small>
                </a>
            </li>
            <li>
                <a href="../mailbox/mailbox.html">
                    <i class="fa fa-envelope"></i> <span>Mailbox</span>
                    <small class="label pull-right bg-yellow">12</small>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Examples</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                    <li><a href="../examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                    <li><a href="../examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                    <li><a href="../examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                    <li><a href="../examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                    <li><a href="../examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                    <li><a href="../examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Multilevel</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li>
            <li><a href="../../documentation/index.html"><i class="fa fa-book"></i> Documentation</a></li>
            <li class="header">LABELS</li>
            <li><a href="#"><i class="fa fa-circle-o text-danger"></i> Important</a></li>
            <li><a href="#"><i class="fa fa-circle-o text-warning"></i> Warning</a></li>
            <li><a href="#"><i class="fa fa-circle-o text-info"></i> Information</a></li>
        </ul-->
    </section>
    <!-- /.sidebar -->
</aside>
