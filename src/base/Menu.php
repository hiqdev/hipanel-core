<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

class Menu extends \hiqdev\menumanager\Menu implements \yii\base\BootstrapInterface
{
    protected $_menus = [];

    public function bootstrap($app)
    {
        foreach ($this->_menus as $menu => $data) {
            $app->menuManager->$menu->addItems($data['items'], $data['where']);
        }
        $app->menuManager->breadcrumbs;  /// forced initilization
    }
}
