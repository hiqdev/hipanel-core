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
use yii\di\Instance;
use yii\helpers\StringHelper;
use yii\web\ForbiddenHttpException;
use yii\web\User;

/**
 * EasyAccessControl provides easy access control based on a list of actions and permissions.
 * Like this:
 * ```
 *   'class' => EasyAccessControl::class,
 *   'actions' => [
 *       'create'    => 'ticket.create',
 *       'answer'    => 'ticket.answer',
 *       'delete'    => 'ticket.delete',
 *       '*'         => 'ticket.read',
 *   ],
 * ```.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class EasyAccessControl extends ActionFilter
{
    public const GUEST = '?';
    public const USER = '@';
    public const ALLOW_ANY = '*';

    /**
     * @var User|array|string|false the user object representing the authentication status or the ID of the user application component
     */
    public $user = 'user';

    /**
     * @var callable a callback that will be called if the access should be denied
     * The signature of the callback should be as follows:
     *
     * ```php
     * function ($action)
     * ```
     */
    public $denyCallback;

    /**
     * @var array a list of actions -> permissions
     */
    public $actions = [];

    /**
     * Initializes user.
     */
    public function init()
    {
        parent::init();
        if ($this->user !== false) {
            $this->user = Instance::ensure($this->user, User::class);
        }
    }

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed
     * @return bool whether the action execution should be continued
     */
    public function beforeAction($action)
    {
        return $this->checkActions($action) ?: $this->denyAccess($action);
    }

    protected function checkActions($action)
    {
        foreach ($this->actions as $names => $permissions) {
            if ($this->matchAction($action, $names)) {
                return $this->checkAllowed($permissions);
            }
        }

        return false;
    }

    protected function matchAction($action, $names)
    {
        if ($names === '*') {
            return true;
        }

        $actions = StringHelper::explode($names, ',', true, true);
        if (in_array($action->id, $actions, true)) {
            return true;
        }

        foreach ($actions as $a) {
            if (strpos($action->id, $a) === 0) {
                return true;
            }
        }

        return false;
    }

    protected function checkAllowed($permissions)
    {
        if (is_bool($permissions)) {
            return $permissions;
        }
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }
        foreach ($permissions as $permission) {
            if ($permission === self::GUEST) {
                return $this->user->getIsGuest();
            }

            if ($permission === self::USER) {
                return !$this->user->getIsGuest();
            }

            if ($permission === self::ALLOW_ANY) {
                return true;
            }

            if ($this->user->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User|false $user the current user or boolean `false` in case of detached User component
     * @throws ForbiddenHttpException if the user is already logged in or in case of detached User component
     */
    protected function denyAccess($action)
    {
        if ($this->denyCallback !== null) {
            call_user_func($this->denyCallback, $action);

            return false;
        }
        if ($this->user !== false && $this->user->getIsGuest()) {
            $this->user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}
