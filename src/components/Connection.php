<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\components;

use Yii;

class Connection extends \hiqdev\hiart\Connection implements ApiConnectionInterface
{
    /**
     * @param mixed $response The response
     * @return null|string
     *  - string: the error text
     *  - null: the response is not an error
     */
    public function checkError($response)
    {
        if ($response !== '0' && $this->isError($response)) {
            $error = $this->getError($response);
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

    private function isError($data)
    {
        return is_array($data) ? array_key_exists('_error', $data) : !$data;
    }

    private function getError($data)
    {
        return isset($data['_error']) ? $data['_error'] : null;
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
            Yii::$app->response->redirect('/site/login');
            Yii::$app->end();
        }

        $token = $identity->getAccessToken();
        if (!$token && $user->loginRequired() !== null) {
            Yii::$app->response->redirect('/site/logout');
            Yii::$app->end();
        }

        return ['access_token' => $token];
    }
}
