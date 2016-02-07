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

use yii\base\InvalidConfigException;

class User extends \yii\web\User
{
    /**
     * @var string the seller login
     */
    public $seller;

    public function not($key)
    {
        $identity = $this->getIdentity();
        if (!$identity) {
            throw new InvalidConfigException();
        }

        return $identity->not($key);
    }

    public function is($key)
    {
        $identity = $this->getIdentity();
        if (!$identity) {
            throw new InvalidConfigException();
        }

        return $identity->is($key);
    }
}
