<?php

namespace hipanel\tests\_support\Step\Acceptance;

use hipanel\tests\_support\Page\Login;

class Admin extends \AcceptanceTester
{
    public function login()
    {
        if ($this->loadSessionSnapshot('login-admin')) {
            return $this;
        }

        $hiam = new Login($this);
        $hiam->login('', '');

        $this->saveSessionSnapshot('login-admin');

        return $this;
    }
}