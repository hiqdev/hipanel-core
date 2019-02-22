<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\controllers;

use hipanel\logic\Impersonator;
use hipanel\models\User;
use hisite\actions\RedirectAction;
use hisite\actions\RenderAction;
use Yii;
use yii\authclient\AuthAction;
use yii\base\Module;
use yii\filters\AccessControl;

/**
 * Site controller.
 */
class SiteController extends \hisite\controllers\SiteController
{
    /** @var string */
    protected $defaultAuthClient = 'hiam';
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
                'url' => ['@client/view', 'id' => Yii::$app->user->identity->id],
            ],
            'lockscreen' => [
                'class' => RenderAction::class,
            ],
            'notification-settings' => [
                'class' => RedirectAction::class,
                'url'   => [
                    '@client/view',
                    'id'    => Yii::$app->user->identity->id,
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
        Yii::$app->user->login($user, Yii::$app->params['login_duration'] ?: 3600 * 24 * 30);
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
        $text = "Up and running.";
        if (isset(Yii::$app->user->identity->id)) {
            $id = Yii::$app->user->identity->id;
            $text .= "\n<h6>User ID: <userId>$id</userId></h6>";
        }

        return $text;
    }
}
