<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

/**
 * @property array url the URL for redirect. Every element can be a callback, which gets the model and $this pointer as arguments
 */
class RedirectAction extends Action
{
    /**
     * @var string|array url to redirect to
     */
    protected $_url;

    /**
     * Collects the URL array, executing callable functions.
     *
     * @return array
     */
    public function getUrl()
    {
        $res = [];
        foreach ($this->_url as $k => $v) {
            $res[$k] = $v instanceof \Closure ? call_user_func($v, $this->getModel(), $this) : $v;
        }
        return $res;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    public function run()
    {
        return $this->controller->redirect($this->url);
    }

}
