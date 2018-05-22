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
        if ($this->loadSessionSnapshot('login-seller')) {
            return $this;
        }

        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->saveSessionSnapshot('login-seller');

        return $this;
    }

    protected function initCredentials()
    {
        [$this->username, $this->password] = $this->getSellertCredentials();
    }
}
