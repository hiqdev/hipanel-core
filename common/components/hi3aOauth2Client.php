<?php

namespace common\components;

use yii\authclient\OAuth2;

/**
 * hi3a allows authentication via hi3a OAuth2.
 *
 * In order to use hi3a you must register your application at <https://hi3a.hipanel.com/>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'hiqdev\hi3aClient\AuthCollection',
 *         'clients' => [
 *             'hi3a' => [
 *                 'class'        => 'hiqdev\hi3aClient\Oauth2Client',
 *                 'site'         => 'sol-hi3a-master.ahnames.com',
 *                 'clientId'     => 'client_id',
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
    /** site for urls generation */
    public $site;

    public function buildUrl ($path,array $params = []) {
        $url = $this->site.'/'.$path;
        return $params ? $this->composeUrl($url,$params) : $url;
    }

    /** inits Urls based on $site */
    public function init () {
        parent::init();
        if (!$this->site) {
            $this->site = 'hi3a.hipanel.com';
        };
        if (strpos($this->site, '://') === false) {
            $this->site = 'https://'.$this->site;
        };
        $defaults = [
            'authUrl'       => 'oauth2/authorize',
            'tokenUrl'      => 'oauth2/token',
            'apiBaseUrl'    => 'api',
        ];
        foreach ($defaults as $k => $v) {
            if (!$this->{$k}) {
                $this->{$k} = $this->buildUrl($v);
            };
        };
    }

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
