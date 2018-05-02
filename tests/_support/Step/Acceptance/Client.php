<?php

namespace hipanel\tests\_support\Step\Acceptance;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Login;

class Client extends AcceptanceTester
{
    public $username = '';
    public $password = '';

    public function login()
    {
        if ($this->loadSessionSnapshot('login-client')) {
            return $this;
        }

        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->saveSessionSnapshot('login-client');

        return $this;
    }
}
