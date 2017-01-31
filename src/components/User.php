<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\components;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;

class User extends \yii\web\User
{
    /**
     * @var string the seller login
     */
    public $seller;

    public function init()
    {
        parent::init();
        if (empty($this->seller)) {
            throw new InvalidConfigException('User "seller" must be set');
        }
    }

    public function not($key)
    {
        $identity = $this->getIdentity();
        if (!$identity) {
            throw new InvalidCallException();
        }

        return $identity->not($key);
    }

    public function is($key)
    {
        $identity = $this->getIdentity();
        if (!$identity) {
            throw new InvalidCallException();
        }

        return $identity->is($key);
    }
    /**
     * @inheritdoc
     * XXX fixes redirect loop when identity is set but the object is empty
     * @return bool
     */
    public function getIsGuest()
    {
        return empty($this->getIdentity()->id);
    }
}
