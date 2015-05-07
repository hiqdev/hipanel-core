<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

class ProxyAction extends Action
{

    /**
     * @var string action to run.
     */
    public $action;

    public function run()
    {
        return $this->controller->runAction($this->action);
    }

}
