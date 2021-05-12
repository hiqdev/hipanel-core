<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\logic;

use hipanel\models\User;
use yii\authclient\BaseOAuth;
use yii\authclient\Collection;
use yii\authclient\OAuth2;
use yii\helpers\Url;
use yii\web\Session;

class Impersonator
{
    public $defaultAuthClient = 'hiam';
    /**
     * @var Session
     */
    private $session;
    /**
     * @var \yii\web\User
     */
    private $user;
    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Session $session, \yii\web\User $user, Collection $collection)
    {
        $this->session = $session;
        $this->user = $user;
        $this->collection = $collection;
    }

    /**
     * Method should be called to generate URL for user redirect.
     *
     * @param string $user_id
     * @return string
     */
    public function buildAuthUrl($user_id)
    {
        return $this->getClient()->buildAuthUrl([
            'redirect_uri' => $this->buildRedirectUri(),
            'user_id' => $user_id,
        ]);
    }

    public function buildRedirectUri(array $getParams = []): string
    {
        $route = array_merge($getParams, [
            '/site/impersonate-auth',
            'authclient' => $this->defaultAuthClient,
        ]);

        return Url::toRoute($route, true);
    }

    /**
     * @return OAuth2 $client
     */
    private function getClient()
    {
        return $this->collection->getClient($this->defaultAuthClient);
    }

    /**
     * Method should be called when authentication succeeded.
     */
    public function impersonateUser(BaseOAuth $client): void
    {
        $attributes = $client->getUserAttributes();
        $identity = new User();
        foreach ($identity->attributes() as $k) {
            if (isset($attributes[$k])) {
                $identity->{$k} = $attributes[$k];
            }
        }
        if ($this->user->getId() === $identity->getId()) {
            return;
        }

        $identity->save();
        $this->session->set('__realId', $this->user->getId());
        $this->user->setIdentity($identity);
        $this->session->set($this->user->idParam, $this->user->getId());
    }

    /**
     * Method should be called when user should be unimpersonated.
     */
    public function unimpersonateUser()
    {
        $realId = $this->session->remove('__realId');
        if ($realId !== null) {
            $this->user->identity->remove();
            $this->session->set($this->user->idParam, $realId);
            $identity = User::findOne($realId);
            $this->user->setIdentity($identity);
            $this->restoreBackedUpToken();
        }
    }

    protected function getStateStorageKeyName(string $name): string
    {
        $client = $this->getClient();

        return get_class($client) . '_' . $client->getId() . '_' . $name;
    }

    protected function restoreBackedUpToken()
    {
        $stateStorage = $this->getClient()->getStateStorage();
        $realTokenKeyName = $this->getStateStorageKeyName('real_token');

        $token = $stateStorage->get($realTokenKeyName);
        $stateStorage->remove($realTokenKeyName);
        if ($token !== null) {
            $tokenKeyName = $this->getStateStorageKeyName('token');
            $stateStorage->set($tokenKeyName, $token);
        }
    }

    /**
     * Method should be called before user redirect to authentication server.
     */
    public function backupCurrentToken()
    {
        $stateStorage = $this->getClient()->getStateStorage();

        $token = $stateStorage->get($this->getStateStorageKeyName('token'));
        $stateStorage->set($this->getStateStorageKeyName('real_token'), $token);
    }

    /**
     * Registers the passed oAuth state as a valid one.
     * Used for push-impersonation.
     *
     * @param string $state
     */
    public function registerAuthState(string $state): void
    {
        $this->getClient()->getStateStorage()->set($this->getStateStorageKeyName('authState'), $state);
    }

    /**
     * @return bool
     */
    public function isUserImpersonated()
    {
        return $this->session->has('__realId');
    }

    /**
     * Impersonates a User using oAuth access code and oAuth state.
     *
     * @param string $code
     * @param string|null $state
     * @throws \yii\web\HttpException
     */
    public function impersonateWithStateAndCode(string $code, string $state = null): void
    {
        $this->registerAuthState($state);
        $token = $this->getClient()->fetchAccessToken($code);
        $this->impersonateUser($this->getClient());
    }
}
