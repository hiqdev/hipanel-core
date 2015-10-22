<?php

namespace hipanel\base;

use hipanel\helpers\ArrayHelper;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\helpers\Inflector;

class AuthManager extends Component
{
    protected $_permissions;

    public function init()
    {
        parent::init();
    }

    public function getPermissions()
    {
        if ($this->_permissions === null) {
            $this->_permissions = Yii::$app->params['permissions'];
        }

        return $this->_permissions;
    }

    public function hasPermission($name, $params = [])
    {
        $allowed = $this->getPermissions()[$name];
        if (is_array($allowed)) {
            foreach ($allowed as $k) {
                $k = trim($k);
                if ($k == $this->type || $k == $this->username) {
                    return true;
                }
            }
        }
        return false;
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
     * Current user username.
     */
    protected $_username;

    public function getUsername()
    {
        if (!$this->_username) {
            $this->_username = $this->getIdentity()->username;
        }
        return $this->_username;
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

    public function checkAccess($userId, $permission, $params = [])
    {
        if ($userId !== $this->id) {
            throw new InvalidParamException("only current user check access is available for the moment");
        }
        return $this->hasPermission($permission, $params) || $this->canDo($permission, $params);
    }

    public function canDo($permission, $params = [])
    {
        $checker = 'can' . Inflector::id2camel($permission);
        return method_exists($this, $checker) ? $this->$checker($params) : false;
    }

}
