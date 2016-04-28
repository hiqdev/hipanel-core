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

class OrientationStorage extends Component
{
    const SESSION_KEY = 'orientationStorage';
    const ORIENTATION_HORIZONTAL = 'horizontal';
    const ORIENTATION_VERTICAL = 'vertical';

    public $defaultOrientation = self::ORIENTATION_HORIZONTAL;

    public $storage = [];

    public function set($route, $orientation)
    {
        $this->storage[$route] = $orientation;
    }

    public function get($route)
    {
        return isset($this->storage[$route]) ? $this->storage[$route] : $this->defaultOrientation;
    }

    /**
     * @param array $options
     * @return OrientationStorage
     */
    public static function instantiate($options = [])
    {
        $storage = Yii::$app->session->get(static::SESSION_KEY);
        if (!$storage instanceof self) {
            $storage = new static($options);
            Yii::$app->session->set(static::SESSION_KEY, $storage);
        }

        return $storage;
    }
}
