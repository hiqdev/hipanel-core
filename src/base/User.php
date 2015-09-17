<?php

namespace hipanel\base;

use Yii;
use yii\web\InvalidConfigException;

class User extends \yii\web\User
{
    public function not($key)
    {
        $identity = $this->getIdentity();
        if (!$identity) {
            throw new InvalidConfigException();
        }

        return $identity->not($key);
    }
}
