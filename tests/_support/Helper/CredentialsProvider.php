<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\_support\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Lib\ModuleContainer;
use Codeception\Module\WebDriver;
use hipanel\helpers\Url;

/**
 * Class CredentialsProvider.
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
            'manager.id', 'manager.login', 'manager.password',
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

    public function getManagerCredentials(): array
    {
        return [$this->config['manager.id'], $this->config['manager.login'], $this->config['manager.password']];
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
        try {
            $currentUrl = $wd->grabFromCurrentUrl();
        } catch (ModuleException $e) {
            // No page opened yet (fresh/blank browser tab) - grabFromCurrentUrl() throws in that case.
            $currentUrl = null;
        }
        if ($currentUrl !== $url) {
            $wd->amOnPage($url);
            // The layout shell loads synchronously, but page content (e.g. the h1
            // callers check right after needPage()) is filled in by a pjax/AJAX
            // request that fires after the initial navigation - without this,
            // callers race the content load and intermittently see a not-yet-filled
            // page (see ContactsCest flake).
            //
            // Waiting for "idle" alone isn't enough: jQuery.active is still 0
            // until that request actually starts, so checking idle first can
            // pass instantly, before the request was even dispatched. Wait for
            // it to start first (tolerating pages with no pjax/AJAX content at
            // all) and only then wait for it to finish.
            try {
                $wd->waitForJS('return !!window.jQuery && window.jQuery.active > 0;', 1);
            } catch (\Facebook\WebDriver\Exception\TimeoutException $e) {
                // No pjax/AJAX request started within the grace window - this
                // page has no further async content to wait out.
            }
            try {
                $wd->waitForJS('return document.readyState === "complete" && (!window.jQuery || window.jQuery.active === 0);', 30);
            } catch (\Facebook\WebDriver\Exception\TimeoutException $e) {
                // Ignore background polling timeouts
            }
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
