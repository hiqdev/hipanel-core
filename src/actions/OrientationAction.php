<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hipanel\base\OrientationStorage;
use Yii;
use yii\base\Action;

class OrientationAction extends Action
{
    public $allowedRoutes = [];

    public function init()
    {
        parent::init();

        foreach ($this->allowedRoutes as &$allowedRoute) {
            $allowedRoute = ltrim(Yii::getAlias($allowedRoute), '/');
        }

        return $this->controller->redirect(Yii::$app->request->getReferrer());
    }

    public function run($route, $orientation)
    {
        $storage = $this->getStorage();
        if ($this->isRouteAllowed($route)) {
            $storage->set($route, $orientation);
        }
    }

    protected function isRouteAllowed($route)
    {
        return in_array($route, $this->allowedRoutes, true);
    }

    /**
     * @return OrientationStorage
     */
    public function getStorage()
    {
        return OrientationStorage::instantiate();
    }
}
