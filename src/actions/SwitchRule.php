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
     * @var boolean whether to save data before running action
     */
    public $save = false;

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

    public function run($postfix = null)
    {
        return $this->runAction($postfix);
    }

    /**
     * Setter for action. Saves the action to the controller.
     *
     * @param mixed $action action config.
     * @param null $postfix
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

            if (!empty($condition[1])) { // Condition if full. Examples: GET html, POST ajax
                $method = $condition[0];
                $type   = $condition[1];
            } else { // Condition is partial. Examples: GET, POST, html, ajax
                if (ctype_upper($condition[0])) {
                    // All letters are uppercase - then it is a request Method (POST, GET)
                    $method = $condition[0];
                    $type   = $requestType;
                } else { // If not - then it is a request type. Examples: html, json
                    $method = $requestMethod;
                    $type   = $condition[0];
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
        if (is_array($request->post('selection'))) {
            return 'selection';
        } elseif ($request->isPjax) {
            return 'pjax';
        } elseif ($request->isAjax) {
            if ($request->post('hasEditable')) {
                return 'editableAjax';
            } elseif ($request->post('pk') && $request->post('name')) {
                return 'xeditable';
            if (is_array($request->post('selection'))) {
                return 'selection';
            } else {
                return 'ajax';
            }
        } else {
            return 'html';
        }
    }
}
