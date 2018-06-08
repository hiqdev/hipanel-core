<?php

namespace hipanel\tests\_support\Step\Acceptance;

use hipanel\tests\_support\Page\Login;

/**
 * Class Seller
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Seller extends Client
{
    public function login()
    {
        if ($this->retrieveSession('login-seller')) {
            return $this;
        }

        $this->restartBrowser();
        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->storeSession('login-seller');

        return $this;
    }

    protected function initCredentials()
    {
        [ $this->id, $this->username, $this->password] = $this->getSellerCredentials();
    }
}
