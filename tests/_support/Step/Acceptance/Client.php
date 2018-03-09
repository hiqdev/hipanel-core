<?php

namespace hipanel\tests\_support\Step\Acceptance;

use hipanel\tests\_support\Page\Login;

class Client extends \AcceptanceTester
{
    public function login()
    {
        if ($this->loadSessionSnapshot('login-client')) {
            return $this;
        }

        $hiam = new Login($this);
        $hiam->login('', '');

        $this->saveSessionSnapshot('login-client');

        return $this;
    }
}