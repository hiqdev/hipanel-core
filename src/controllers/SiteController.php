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

use hipanel\models\User;
use hisite\actions\RedirectAction;
use hisite\actions\RenderAction;
use Yii;
use yii\authclient\AuthAction;
use yii\filters\AccessControl;

/**
 * Site controller.
 */
class SiteController extends \hisite\controllers\SiteController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'loginRequired' => [
                'class' => AccessControl::class,
                'only' => ['profile'],
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
        ]);
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

        return $this->redirect(['/site/auth', 'authclient' => 'hiam']);
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
}
