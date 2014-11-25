<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\widgets\Alert;

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('//layouts/head') ?>
    </head>
    <body class="skin-black">
    <?php $this->beginBody(); ?>

        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?= Yii::$app->homeUrl ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?= Yii::$app->name ?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"><?= 'Toggle navigation' ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?= 'User Name' ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <?= Html::img('/adminlte/img/avatar5.png', ['class' => 'img-circle', 'alt' => Yii::$app->user->identity->username]) ?>
                                    <p>
                                        <?= 'User Name' ?> - <?= 'Administrator' ?>
                                        <small><?= Yii::t('app', 'Member since') ?> <?= '2014-11-24' ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?= Html::a(
                                            'Profile',
                                            ['/users/default/update', 'id' => Yii::$app->user->id],
                                            ['class' => 'btn btn-default btn-flat']
                                        ) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?= Html::a(
                                            'Sign out',
                                            ['/users/user/logout'],
                                            ['class' => 'btn btn-default btn-flat']
                                        ) ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <?php // if (Yii::$app->user->identity->profile->avatar_url) : ?>
                            <div class="pull-left image">
                                <?= Html::img('/adminlte/img/avatar5.png', ['class' => 'img-circle', 'alt' => 'User Name']) ?>
                            </div>
                        <?php // endif; ?>
                        <div class="pull-left info">
                            <p>
                                <?= Yii::t('app', 'Hello, {name}', ['name' => 'User Name']) ?>
                            </p>
                            <a>
                                <i class="fa fa-circle text-success"></i> <?= Yii::t('app', 'Online') ?>
                            </a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?= $this->render('//layouts/sidebar-menu') ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?= $this->title ?>
                        <?php if (isset($this->params['subtitle'])) : ?>
                            <small><?= $this->params['subtitle'] ?></small>
                        <?php endif; ?>
                    </h1>
                    <?= Breadcrumbs::widget(
                        [
                            'homeLink' => [
                                'label' => '<i class="fa fa-dashboard"></i> ' . Yii::t('app', 'Home'),
                                'url' => '/'
                            ],
                            'encodeLabels' => false,
                            'tag' => 'ol',
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                        ]
                    ) ?>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?= Alert::widget(); ?>
                    <?= $content ?>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>