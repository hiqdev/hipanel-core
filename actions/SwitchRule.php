<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

/**
 * HiPanel basic action.
 */
class SwitchRule extends \yii\base\Component
{

    /**
     * @var Action parent action.
     */
    public $parent;

    /**
     * @var string rule unique name and condition if not given explicitly.
     */
    public $name;

    /**
     * @var mixed rule condition, can be object in future.
     */
    protected $_condition;

    public function setCondition($condition)
    {
        $this->_condition = $condition;
    }

    /**
     * If no condition the name is used instead.
     */
    public function getCondition()
    {
        return $this->_condition ?: $this->name;
    }

    /**
     * Synthetic ID for the ruled action.
     */
    public function getId($postfix = null)
    {
        return $this->parent->id . ' ' . $this->name . ($postfix ? ' '.$postfix : '');
    }

    /**
     * Run action
     */
    public function runAction($postfix = null)
    {
        $longId = $this->getId($postfix);
        $action = $this->parent->controller->hasInternalAction($longId) ? $longId : $this->id;
        return $this->parent->controller->runAction($action);
    }

    /**
     * Setter for action. Saves the action to the controller.
     * @param mixed $action action config.
     */
    public function setAction($action, $postfix = null)
    {
        $this->parent->controller->setInternalAction($this->getId($postfix), $action);
    }

    public function setSuccess($success)
    {
        $this->setAction($success);
    }

    public function setError($error)
    {
        $this->setAction($error, 'error');
    }

    public function isApplicable()
    {
        if ($this->condition === 'default') {
            return true;
        }
        /// actual checks go here

        return false;
    }

}
