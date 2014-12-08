<?php

namespace common\components;

use yii\authclient\OAuth2;

/**
 * hi3a allows authentication via hi3a OAuth2.
 *
 * In order to use hi3a you must register your application at <https://hi3a.hiqdev.com/>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'hi3a' => [
 *                 'class' => 'hi3a',
 *                 'clientId' => 'client_id',
 *                 'clientSecret' => 'client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 */
class hi3aOauth2Client extends OAuth2
{
    /** @inheritdoc */
    public $authUrl = 'https://hi3a.hiqdev.com/oauth2/authorize';
    /** @inheritdoc */
    public $tokenUrl = 'https://hi3a.hiqdev.com/oauth2/token';
    /** @inheritdoc */
    public $apiBaseUrl = 'https://hi3a.hiqdev.com/api';


    /** @inheritdoc */
    protected function initUserAttributes () {
        return $this->getAccessToken()->getParam('user_attributes');
    }

    /** @inheritdoc */
    protected function apiInternal ($accessToken, $url, $method, array $params, array $headers) {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }
        $params['oauth_token'] = $accessToken->getToken();

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /** @inheritdoc */
    protected function defaultName  () { return 'hi3a'; }

    /** @inheritdoc */
    protected function defaultTitle () { return 'hi3a'; }
}
