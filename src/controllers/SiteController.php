<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\controllers;

use hipanel\helpers\UserHelper;
use hipanel\logic\Impersonator;
use hipanel\models\User;
use hisite\actions\RedirectAction;
use hisite\actions\RenderAction;
use Yii;
use hiam\authclient\AuthAction;
use yii\base\Module;
use yii\caching\MemCache;
use yii\filters\AccessControl;

/**
 * Site controller.
 */
class SiteController extends \hisite\controllers\SiteController
{
    /** @var string */
    public $defaultAuthClient = 'hiam';
    /**
     * @var Impersonator
     */
    private $impersonator;

    public function __construct(string $id, Module $module, Impersonator $impersonator, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->impersonator = $impersonator;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'loginRequired' => [
                'class' => AccessControl::class,
                'only' => ['profile', 'notification-settings'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'impersonate-auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this->impersonator, 'impersonateUser'],
            ],
            'index' => [
                'class' => RedirectAction::class,
                'url'   => ['/dashboard/dashboard'],
            ],
            'profile' => [
                'class' => RedirectAction::class,
                'url' => [
                    '@client/view',
                    'id' => UserHelper::getId(),
                ],
            ],
            'lockscreen' => [
                'class' => RenderAction::class,
            ],
            'ip-restriction-settings' => [
                'class' => RedirectAction::class,
                'url'   => [
                    '@client/view',
                    'id'    => UserHelper::getId(),
                    '#'     => 'ip_restriction_settings',
                ],
            ],
            'notification-settings' => [
                'class' => RedirectAction::class,
                'url'   => [
                    '@client/view',
                    'id'    => UserHelper::getId(),
                    '#'     => 'notification_settings',
                ],
            ],
        ]);
    }

    public function actionUnimpersonate()
    {
        if ($this->impersonator->isUserImpersonated()) {
            $this->impersonator->unimpersonateUser();
        }

        return $this->redirect('/');
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $user = new User();
        foreach ($user->attributes() as $k) {
            if (isset($attributes[$k])) {
                $user->{$k} = $attributes[$k];
            }
        }
        $user->save();
        Yii::$app->user->login($user, Yii::$app->params['login_duration'] ?? 3600 * 24 * 30);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site/index']);
        }

        return $this->redirect(['/site/auth', 'authclient' => $this->defaultAuthClient]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        $back = Yii::$app->request->getHostInfo();
        $url = Yii::$app->authClientCollection->getClient()->buildUrl('site/logout', compact('back'));

        return $this->redirect($url);
    }

    public function actionSignup()
    {
        $back = Yii::$app->request->getHostInfo();
        $url = Yii::$app->authClientCollection->getClient()->buildUrl('site/signup', compact('back'));

        return $this->redirect($url);
    }

    public function actionPushImpersonateAuth()
    {
        if ($this->impersonator->isUserImpersonated()) {
            $this->impersonator->unimpersonateUser();
        }
        $this->impersonator->backupCurrentToken();
        $this->impersonator->impersonateWithStateAndCode(
            $this->request->get('code'),
            $this->request->get('state')
        );

        return $this->goHome();
    }

    public function actionImpersonate($user_id)
    {
        if ($this->impersonator->isUserImpersonated()) {
            $this->impersonator->unimpersonateUser();
        }

        $this->impersonator->backupCurrentToken();

        return $this->redirect($this->impersonator->buildAuthUrl($user_id));
    }

    public function actionHealthcheck()
    {
        $text = 'Up and running.';
        if (Yii::$app->cache instanceof MemCache) {
            $text .= "\n<h6>Cache is OK</h6>";
        } else {
            $text .= "\n<h6>Cache is ABSENT</h6>";
        }
        if (isset(Yii::$app->user->identity->id)) {
            $id = Yii::$app->user->identity->id;
            $text .= "\n<h6>User ID: <userId>$id</userId></h6>";
        }

        return $text;
    }
}
