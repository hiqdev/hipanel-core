<?php

namespace hipanel\tests\_support\Step\Acceptance;

use Codeception\Scenario;
use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Login;

class Client extends AcceptanceTester
{
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->initCredentials();
    }

    protected $username;
    protected $password;
    public $id;

    public function login()
    {
        try {
            if ($this->loadSessionSnapshot('login-client')) {
                return $this;
            }
        } catch (\Facebook\WebDriver\Exception\UnknownServerException $exception) {
            // User is already logged in, but trying to open a session on a page that is not loaded
            return $this;
        }

        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->saveSessionSnapshot('login-client');

        return $this;
    }

    protected function initCredentials()
    {
        [$this->username, $this->password, $this->id] = $this->getClientCredentials();
    }
}
