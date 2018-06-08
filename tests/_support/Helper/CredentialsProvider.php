<?php
namespace hipanel\tests\_support\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Lib\ModuleContainer;
use Codeception\Module\WebDriver;
use hipanel\helpers\Url;

/**
 * Class CredentialsProvider
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class CredentialsProvider extends \Codeception\Module
{
    /**
     * @var array
     */
    private $sessionSnapshots = [];

    /**
     * @var string
     */
    private $filename = WEBAPP_ROOT_DIR . '/tests/_data/sessionSnapshots.data';

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $this->requiredFields = [
            // Client
            'client.id', 'client.login', 'client.password',
            'seller.id', 'seller.login', 'seller.password',
            'admin.id', 'admin.login', 'admin.password',
        ];
    }

    public function getClientCredentials(): array
    {
        return [$this->config['client.id'], $this->config['client.login'], $this->config['client.password']];
    }


    public function getSellerCredentials(): array
    {
        return [$this->config['seller.id'], $this->config['seller.login'], $this->config['seller.password']];
    }

    public function getAdminCredentials(): array
    {
        return [$this->config['admin.id'], $this->config['admin.login'], $this->config['admin.password']];
    }

    public function restartBrowser()
    {
        /** @var WebDriver $wd */
        $wd = $this->getModule('WebDriver');
        $wd->_restart();
    }

    public function needPage(string $url): void
    {
        /** @var WebDriver $wd */
        $wd = $this->getModule('WebDriver');
        $currentUrl = $wd->grabFromCurrentUrl();
        if ($currentUrl !== $url) {
            $wd->amOnPage($url);
        }
    }

    public function storeSession(string $name): void
    {
        /** @var WebDriver $wd */
        $wd = $this->getModule('WebDriver');
        $wd->saveSessionSnapshot($name);

        $reflection = new \ReflectionObject($wd);
        $property = $reflection->getProperty('sessionSnapshots');
        $property->setAccessible(true);
        $snapshots = $property->getValue($wd);
        $property->setAccessible(false);

        $this->sessionSnapshots[$name] = $snapshots[$name];
        $this->persistSessionSnapshots();
    }

    public function retrieveSession(string $name): bool
    {
        $this->readSessionSnapshots();

        /** @var WebDriver $wd */
        $wd = $this->getModule('WebDriver');

        try {
            $wd->_getCurrentUri();
        } catch (ModuleException $e) {
            $wd->amOnPage(Url::to('/site/healthcheck'));
        }
        $reflection = new \ReflectionObject($wd);
        $property = $reflection->getProperty('sessionSnapshots');
        $property->setAccessible(true);
        $property->setValue($wd, array_merge($property->getValue($wd), $this->sessionSnapshots));
        $property->setAccessible(false);

        return $wd->loadSessionSnapshot($name);
    }

    private function persistSessionSnapshots()
    {
        file_put_contents($this->filename, serialize($this->sessionSnapshots));
    }

    private function readSessionSnapshots()
    {
        if (!file_exists($this->filename)) {
            return;
        }

        $expires = strtotime('+1 day', filemtime($this->filename));
        $now = time();
        if ($now > $expires) {
            unlink($this->filename);
        } else {
            $this->sessionSnapshots = unserialize(file_get_contents($this->filename));
        }
    }
}
