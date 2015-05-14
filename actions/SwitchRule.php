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
 *
 * @property mixed condition
 */
class SwitchRule extends \yii\base\Component
{

    /**
     * @var Action parent action.
     */
    public $switch;

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
     *
     * @param string $postfix
     * @return string
     */
    public function getId($postfix = null)
    {
        return $this->switch->id . ' ' . $this->name . ($postfix ? ' ' . $postfix : '');
    }

    /**
     * Run action
     *
     * @param string $postfix
     * @return mixed result of the action
     */
    public function runAction($postfix = null)
    {
        $longId = $this->getId($postfix);
        $action = $this->switch->controller->hasInternalAction($longId) ? $longId : $this->id;
        return $this->switch->controller->runAction($action);
    }

    /**
     * Setter for action. Saves the action to the controller.
     *
     * @param mixed $action action config.
     */
    public function setAction($action, $postfix = null)
    {
        if (!isset($action['parent'])) {
            $action['parent'] = $this->switch;
        }
        $this->switch->controller->setInternalAction($this->getId($postfix), $action);
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

        $requestMethod = Yii::$app->request->method;
        $requestType   = $this->getRequestType();

        $conditions = array_map('trim', explode('|', $this->condition));
        foreach ($conditions as $condition) {
            $condition = explode(' ', $condition);
            if (!empty($condition[1])) {
                $method = $condition[0];
                $type   = $condition[1];
            } else {
                if (ctype_upper($condition)) {
                    $method = $condition;
                    $type   = $requestType;
                } else {
                    $method = $requestMethod;
                    $type   = $condition;
                }
            }

            if ($method == $requestMethod && $type == $requestType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        $request = Yii::$app->request;
        if ($request->isPjax) {
            return 'pjax';
        } elseif ($request->isAjax && array_key_exists('application/json', $request->getAcceptableContentTypes())) {
            if ($request->post('hasEditable')) {
                return 'editableAjax';
            } else {
                return 'ajax';
            }
        } else {
            return 'html';
        }
    }
}
