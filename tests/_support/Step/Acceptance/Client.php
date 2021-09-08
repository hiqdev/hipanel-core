<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

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
        $this->loadOrLogin();

        return $this->findId();
    }

    protected function loadOrLogin()
    {
        $sessionName = 'login-' . $this->getClientType();
        try {
            if ($this->retrieveSession($sessionName)) {
                return $this;
            }
        } catch (\Facebook\WebDriver\Exception\UnknownServerException $exception) {
            // User is already logged in, but trying to open a session on a page that is not loaded
            return $this;
        } catch (\Throwable $exception) {
            codecept_debug('DEBUG: ' . get_class($exception) . ': ' . $exception->getMessage());
            return $this;
        }

        $this->restartBrowser();
        $hiam = new Login($this);
        $hiam->login($this->username, $this->password);
        $this->storeSession($sessionName);

        return $this;
    }

    protected function findId(): self
    {
        if (empty($this->id)) {
            $this->amOnPage('/site/healthcheck');
            $this->id = $this->grabTextFrom('userId');
            if (!$this->id) {
                throw new \Exception('failed detect user ID');
            }
        }

        return $this;
    }

    public function logout(AcceptanceTester $I): void
    {
        $I->click("//li[@class='dropdown user user-menu']/a[@class='dropdown-toggle']");
        $I->clickLink('Sign out');
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
        $func = 'get' . $this->getClientType() . 'Credentials';
        [$this->id, $this->username, $this->password] = $this->{$func}();
    }
}
