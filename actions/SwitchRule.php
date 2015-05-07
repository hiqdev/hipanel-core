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
    public function getId()
    {
        return $this->parent->id . ' ' . $this->name;
    }

    /**
     * Setter for action. Saves the action to the controller.
     * @param mixed $action action config.
     */
    public function setAction($action)
    {
        $this->parent->controller->setInternalAction($this->id, $action);
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
