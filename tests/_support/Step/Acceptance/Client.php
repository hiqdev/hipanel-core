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

    public $id;
    protected $username;
    protected $password;

    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function login()
    {
        try {
            if ($this->retrieveSession('login-client')) {
                return $this;
            }
        } catch (\Facebook\WebDriver\Exception\UnknownServerException $exception) {
            // User is already logged in, but trying to open a session on a page that is not loaded
            return $this;
        }

        $this->restartBrowser();
        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->storeSession('login-client');

        return $this;
    }

    protected function initCredentials()
    {
        [$this->id, $this->username, $this->password] = $this->getClientCredentials();
    }
}
