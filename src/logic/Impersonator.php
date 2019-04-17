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

use hiam\authclient\HiamClient;
use hipanel\models\User;
use yii\authclient\Collection;
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
            'redirect_uri' => Url::toRoute(['/site/impersonate-auth', 'authclient' => $this->defaultAuthClient], true),
            'user_id' => $user_id,
        ]);
    }

    /**
     * @return \hiam\authclient\HiamClient $client
     */
    private function getClient()
    {
        return $this->collection->getClient($this->defaultAuthClient);
    }

    /**
     * Method should be called when authentication succeeded.
     * @param HiamClient $client
     */
    public function impersonateUser(HiamClient $client)
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

    protected function restoreBackedUpToken()
    {
        $token = $this->getClient()->getState('real_token');
        $this->getClient()->removeState('real_token');
        if ($token !== null) {
            $this->getClient()->setState('token', $token);
        }
    }

    /**
     * Method should be called before user redirect to authentication server.
     */
    public function backupCurrentToken()
    {
        $token = $this->getClient()->getState('token');
        $this->getClient()->setState('real_token', $token);
    }

    /**
     * @return bool
     */
    public function isUserImpersonated()
    {
        return $this->session->has('__realId');
    }
}
