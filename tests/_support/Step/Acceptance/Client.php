<?php

namespace hipanel\tests\_support\Step\Acceptance;

class Client extends \AcceptanceTester
{
    protected $username = '';

    protected $password = '';

    public function login()
    {
        $I = $this;
        if ($I->loadSessionSnapshot('login-client')) {
            return;
        }
        $I->wantTo('login as Client');
        $I->amOnPage('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm' => [
                'username' => $this->username,
                'password' => $this->password,
            ]
        ]);
        $I->see($this->username, '.navbar-custom-menu li.dropdown.user.user-menu a span.hidden-xs');
        $I->saveSessionSnapshot('login-client');
    }
}