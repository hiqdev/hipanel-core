<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
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
