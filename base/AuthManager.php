<?php

namespace hipanel\base;

use hipanel\helpers\ArrayHelper;
use Yii;

class AuthManager extends \yii\base\Component
{

    public function canSeeSeller()
    {
        return $this->canSupport();
    }

    public function canSupport()
    {
        static $types = ['support' => 1, 'admin' => 1, 'manager' => 1, 'reseller' => 1, 'owner' => 1];
        return $this->isType($types);
    }

    public function canAdmin()
    {
        static $types = ['admin' => 1, 'owner' => 1];
        return $this->isType($types);
    }

    public function canManage()
    {
        static $types = ['manager' => 1, 'reseller' => 1, 'owner' => 1];
        return $this->isType($types);
    }

    protected $_type;

    public function getType()
    {
        if (!$this->_type) {
            $this->_type = Yii::$app->user->identity->type;
        }
        return $this->_type;
    }

    public function isType($list)
    {
        return ArrayHelper::ksplit($list)[$this->getType()];
    }
}
