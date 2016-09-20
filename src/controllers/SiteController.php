<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\controllers;

use hipanel\models\User;
use hisite\actions\RenderAction;
use hisite\actions\RedirectAction;
use Yii;
use yii\authclient\AuthAction;
use yii\web\BadRequestHttpException;

/**
 * Site controller.
 */
class SiteController extends \hisite\controllers\SiteController
{
    /**
     * {@inheritdoc}
     */
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
                'url'   => function () {
                    $user = Yii::$app->user;

                    return $user->isGuest ? ['/site/login'] : ['@client/view', 'id' => $user->identity->id];
                },
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
        Yii::$app->user->login($user, 3600 * 24 * 30);
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
        $back = Yii::$app->request->getHostInfo();
        $url = Yii::$app->authClientCollection->getClient()->buildUrl('site/logout', compact('back'));
        Yii::$app->user->logout();

        return Yii::$app->response->redirect($url);
    }

    public function actionSignup()
    {
        $back = Yii::$app->request->getHostInfo();
        $url = Yii::$app->authClientCollection->getClient()->buildUrl('site/signup', compact('back'));

        return Yii::$app->response->redirect($url);
    }

}
