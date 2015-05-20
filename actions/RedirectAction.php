<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;
use yii\helpers\Url;

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
        if ($this->_url instanceof \Closure) {
            return call_user_func($this->_url, $this, $this->getModel());
        }
        return $this->_url;
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
