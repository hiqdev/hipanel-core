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

use Codeception\TestInterface;

class WaitHelper extends \Codeception\Module
{
    private $webDriver = null;
    private $webDriverModule = null;

    /**
     * @var int Default delay to wait while page updating
     */
    protected int $defaultDelay = 1;

    public function _before(TestInterface $test)
    {
        if (!$this->hasModule('WebDriver') && !$this->hasModule('Selenium2')) {
            throw new \Exception('PageWait uses the WebDriver. Please be sure that this module is activated.');
        }

        // Use WebDriver
        if ($this->hasModule('WebDriver')) {
            $this->webDriverModule = $this->getModule('WebDriver');
            $this->webDriver = $this->webDriverModule->webDriver;
        }
    }

    /**
     * @param int $timeOut
     * @throws \Codeception\Exception\ModuleException
     */
    public function waitForPageUpdate(int $timeOut = 180): void
    {
        $this->webDriverModule = $this->getModule('WebDriver');

        try {
            $this->webDriverModule->waitForJS('return $.active == 0;', $timeOut);

            $this->webDriverModule->debug('JS check passed: no active requests.');
        } catch (\Facebook\WebDriver\Exception\JavascriptErrorException $exception) {
            $this->webDriverModule->wait($this->defaultDelay);

            $this->webDriverModule->debug('JS Error: ' . $exception->getMessage());
        }
    }

    public function waitPageLoad($timeout = 10)
    {
        $this->webDriverModule->waitForJs('return document.readyState == "complete"', $timeout);
        $this->waitAjaxLoad($timeout);
    }

    public function waitAjaxLoad($timeout = 10)
    {
        $this->webDriverModule->waitForJS('return !!window.jQuery && window.jQuery.active == 0;', $timeout);
        $this->webDriverModule->wait($this->defaultDelay);
    }
}
