<?php

namespace hipanel\tests\_support\Step\Acceptance;

use hipanel\tests\_support\Page\Login;

class Admin extends Client
{
    public function login()
    {
        if ($this->loadSessionSnapshot('login-admin')) {
            return $this;
        }

        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->saveSessionSnapshot('login-admin');

        return $this;
    }

    protected function initCredentials()
    {
        [$this->username, $this->password, $this->id] = $this->getAdminCredentials();
    }
}
