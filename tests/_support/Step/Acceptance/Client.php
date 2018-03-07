<?php

namespace hipanel\tests\Step\Acceptance;

class Client extends \AcceptanceTester
{
    protected $username = 'tofid@hiqdev.com';

    protected $password = '123123';

    public function login()
    {
        $I = $this;
        if ($I->loadSessionSnapshot('login-client')) {
            return;
        }
        $I->wantTo('login as Client');
        $I->amOnPage('/site/login');
        $I->wait(10);
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