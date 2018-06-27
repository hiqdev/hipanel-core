<?php

namespace hipanel\tests\_support\Step\Acceptance;

use hipanel\tests\_support\Page\Login;

/**
 * Class Manager
 *
 * @author Pavlo Kolomiyets <pkolomiy@gmail.com>
 */
class Manager extends Client
{
    public function login()
    {
        if ($this->retrieveSession('login-manager')) {
            return $this;
        }

        $this->restartBrowser();
        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->storeSession('login-manager');

        return $this;
    }

    protected function initCredentials()
    {
        [$this->id, $this->username, $this->password] = $this->getManagerCredentials();
    }
}
