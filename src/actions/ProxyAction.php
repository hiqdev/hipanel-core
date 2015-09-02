<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;
use yii\helpers\Url;

/**
 * Class ProxyAction
 *
 * @property mixed pjaxUrl the url, which will be set to the `X-PJAX-URL` HTTP header, if request is PJAX.
 * Boolean (default true) - whether to set to the header URL, which is based on [[action]] and [[params]]
 * Array - direct
 * @property array|callable params params, that will be passed to [[action]] arguments when it will be called
 * @package hipanel\actions
 */
class ProxyAction extends Action
{

    /**
     * @var string action to run
     */
    public $action;

    /**
     * @var array
     * @see params
     */
    public $_params = [];

    /**
     * @var boolean
     * @see pjaxUrl
     */
    public $_pjaxUrl = true;

    /**
     * @return array
     */
    public function getParams()
    {
        if ($this->_params instanceof \Closure) {
            return call_user_func($this->_params, $this, $this->getModel());
        }
        return $this->_params;
    }

    /**
     * @return bool|string
     */
    public function getPjaxUrl()
    {
        if ($this->_pjaxUrl instanceof \Closure) {
            $url = call_user_func($this->_pjaxUrl, $this);
        } elseif ($this->_pjaxUrl == true) {
            $url = (array)$this->action + (array)$this->params;
        } elseif (is_array($this->_pjaxUrl) || is_string($this->_pjaxUrl)){
            $url = $this->_pjaxUrl;
        } else {
            return false;
        }

        return Url::to($url);
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        if ($this->pjaxUrl && $this->parent->rule->requestType == 'pjax') {
            Yii::$app->response->getHeaders()->add('X-PJAX-URL', $this->pjaxUrl);
        }
        return $this->controller->runAction($this->action, $this->params);
    }

    /**
     * @param mixed $pjaxUrl
     */
    public function setPjaxUrl($pjaxUrl)
    {
        $this->_pjaxUrl = $pjaxUrl;
    }
}
