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

use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;

class User extends \yii\web\User
{
    public $isGuestAllowed = false;

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

    /**
     * Prepares authorization data.
     * Redirects to authorization if necessary.
     * @return array
     */
    public function getAuthData()
    {
        if ($this->isGuest) {
            if ($this->isGuestAllowed) {
                return [];
            } else {
                $this->redirectLogin();
            }
        }

        $token = $this->identity->getAccessToken();
        if (empty($token)) {
            /// logout() is very important here, else - redirect loop
            $this->logout();
            $this->redirectLogin();
        }

        return ['access_token' => $token];
    }

    public function redirectLogin()
    {
        $this->loginRequired();
        Yii::$app->end();
    }
}
