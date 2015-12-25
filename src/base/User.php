<?php

namespace hipanel\base;

use Yii;
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
