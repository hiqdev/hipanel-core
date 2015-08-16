<?php

namespace hipanel\base;

use hipanel\helpers\ArrayHelper;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;

class AuthManager extends Component
{

    public function init()
    {
        parent::init();
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

    public function canResell()
    {
        static $types = ['reseller' => 1, 'owner' => 1];
        return $this->isType($types);
    }

    public function canOwn()
    {
        static $types = ['owner' => 1];
        return $this->isType($types);
    }

    /**
     * Current user.
     */
    protected $_user;

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = Yii::$app->user;
        }
        return $this->_user;
    }

    /**
     * Current user.
     */
    protected $_identity;

    public function getIdentity()
    {
        if (!$this->_identity) {
            $this->_identity = $this->getUser()->identity;
        }
        return $this->_identity;
    }

    /**
     * Current user id.
     */
    protected $_id;

    public function getId()
    {
        if (!$this->_id) {
            $this->_id = $this->getIdentity()->id;
        }
        return $this->_id;
    }

    /**
     * Current user type.
     */
    protected $_type;

    public function getType()
    {
        if (!$this->_type) {
            $this->_type = $this->getIdentity()->type;
        }
        return $this->_type;
    }

    public function isType($list, $type = null)
    {
        $type = is_null($type) ? $this->getType() : $type;
        return ArrayHelper::ksplit($list)[$type];
    }

    public function checkAccess($userId, $permissionName, $params = [])
    {
        if ($userId !== $this->id) {
            throw new InvalidParamException("only current user check access is available for the moment");
        }
        return $this->{'can' . ucfirst($permissionName)}($params);
    }
}
