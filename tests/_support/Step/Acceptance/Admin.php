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
        // logging in
        $I->amOnPage('/site/login');
        $I->wait(10);
//        $I->submitForm('#login-form', [
//            'LoginForm' => [
//                'username' => $this->username,
//                'password' => $this->password,
//            ]
//        ]);
//        $I->see($this->username, '.navbar');
//        // saving snapshot
//        $I->saveSessionSnapshot('login-admin');
    }

}