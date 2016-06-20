<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use Yii;

class Connection extends \hiqdev\hiart\Connection
{
    /**
     * @param mixed $response The response
     * @return null|string
     *  - string: the error text
     *  - null: the response is not an error
     */
    public function checkError($response)
    {
        if ($response !== '0' && Err::is($response)) {
            $error = Err::get($response);
            if (empty($error)) {
                return 'unknown api error';
            } elseif ($error === 'invalid_token') {
                Yii::$app->user->logout();
                Yii::$app->response->refresh()->send();
                Yii::$app->end();
            } else {
                return $error;
            }
        }

        return null;
    }

    /**
     * Prepares authorization data.
     * If user is not authorized redirects to authorization.
     * @return array
     */
    public function getAuth()
    {
        if ($this->_disabledAuth) {
            return [];
        }

        $user  = Yii::$app->user;

        $identity = $user->identity;
        if ($identity === null) {
            Yii::$app->response->redirect('/site/logout');
            Yii::$app->end();
        }

        $token = $identity->getAccessToken();
        if (!$token && $user->loginRequired() !== null) {
            Yii::$app->response->redirect('/site/logout');
            Yii::$app->end();
        }

        if ($user->identity) {
            return ['access_token' => $token];
        }

        if ($user->loginRequired() !== null) {
            Yii::trace('Login is required. Redirecting to the login page', 'hipanel');
            Yii::$app->response->send();
            Yii::$app->end();
        }

        return [];
    }
}
