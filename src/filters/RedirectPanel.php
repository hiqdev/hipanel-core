<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\helpers\StringHelper;

/**
 * EasyAccessControl provides easy access control based on a list of actions and permissions.
 * Like this:
 * ```
 *   'class' => RedirectPanel::class,
 *   'actions' => 'index,view',
 * ```.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class RedirectPanel extends ActionFilter
{
    /**
     * @var string a list of actions requiring redirect to panel
     */
    public $actions = '';

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed
     * @return bool whether the action execution should be continued
     */
    public function beforeAction($action)
    {
        if ($this->getModule()->isPanel()) {
            return true;
        }

        return $this->matchAction($action, $this->actions) ? $this->getModule()->redirectPanel() : true;
    }

    protected function matchAction($action, $names)
    {
        if ($names === '*') {
            return true;
        }

        return in_array($action->id, StringHelper::explode($names, ',', true, true), true);
    }

    protected $_module;

    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('hipanel');
        }

        return $this->_module;
    }
}
