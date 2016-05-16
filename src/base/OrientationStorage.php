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

use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\di\Instance;
use yii\web\Session;

class OrientationStorage extends Component
{
    const ORIENTATION_HORIZONTAL = 'horizontal';
    const ORIENTATION_VERTICAL = 'vertical';

    public $sessionKey = 'orientationStorage';

    public $defaultOrientation = self::ORIENTATION_HORIZONTAL;

    /**
     * @var array
     */
    protected $storage = [];

    /**
     * @var string session component name
     */
    public $session = 'session';

    protected function getSession()
    {
        return Yii::$app->getSession();
    }

    protected function getStorage()
    {
        $session = $this->getSession();

        if ($session->has($this->sessionKey)) {
            $this->storage = $session->get($this->sessionKey);
        }
    }

    protected function saveStorage()
    {
        $session = $this->getSession();
        $session->set($this->sessionKey, $this->storage);
    }

    public function set($route, $orientation)
    {
        $this->storage[$route] = $orientation;
        $this->saveStorage();
    }

    public function get($route)
    {
        $this->getStorage();
        return isset($this->storage[$route]) ? $this->storage[$route] : $this->defaultOrientation;
    }
}
