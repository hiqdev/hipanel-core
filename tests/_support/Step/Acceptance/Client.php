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
        $sessionName = 'login-' . $this->getClientType();
        try {
            if ($this->retrieveSession($sessionName)) {
                return $this;
            }
        } catch (\Facebook\WebDriver\Exception\UnknownServerException $exception) {
            // User is already logged in, but trying to open a session on a page that is not loaded
            return $this;
        }

        $this->restartBrowser();
        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);

        $this->amOnPage('/site/healthcheck');
        $this->id = $this->grabTextFrom('userId');
        if (!$this->id) {
            throw new \Exception('failed detect user ID');
        }
        $this->storeSession($sessionName);

        return $this;
    }

    protected $clientType;

    protected function getClientType(): string
    {
        if ($this->clientType === null) {
            $this->clientType = strtolower((new \ReflectionClass($this))->getShortName());
        }

        return $this->clientType;
    }

    protected function initCredentials()
    {
        $fun = 'get' . $this->getClientType() . 'Credentials';
        [$this->id, $this->username, $this->password] = $this->{$func}();
    }
}
