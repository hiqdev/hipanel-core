<?php

namespace hipanel\tests\Step\Acceptance;

class Admin extends \AcceptanceTester
{
    protected $username = '';

    protected $password = '';

    public function login()
    {
        $I = $this;
        if ($I->loadSessionSnapshot('login-admin')) {
            return;
        }
        $I->amOnPage('/site/login');
        $I->wait(3);
        $I->submitForm('#login-form', [
            'LoginForm' => [
                'username' => $this->username,
                'password' => $this->password,
            ]
        ]);
        $I->see($this->username, '.navbar-custom-menu li.dropdown.user.user-menu a span.hidden-xs');
        $I->saveSessionSnapshot('login-admin');
    }

}