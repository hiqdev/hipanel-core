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

    /**
     * @var array params
     */
    public $_params;

    /**
     * @return array
     */
    public function getParams () {
        if ($this->_params instanceof \Closure) {
            return call_user_func($this->params, $this, $this->getModel());
        }
        return $this->_params;
    }

    /**
     * @param $params
     */
    public function setParams ($params) {
        $this->_params = $params;
    }

    public function run()
    {
        return $this->controller->runAction($this->action, $this->params);
    }

}
