<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

class RedirectAction extends Action
{

    /**
     * @var string|array url to redirect to.
     */
    protected $_url;

    public function getUrl()
    {
        $res = [];
        foreach ($this->_url as $k => $v) {
            $res[$k] = $v instanceof \Closure ? call_user_func($v,$this->model, $this) : $v;
        }
        return $res;
    }

    public function run()
    {
        return $this->controller->redirect($this->url);
    }

}
