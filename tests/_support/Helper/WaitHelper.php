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

class WaitHelper extends \Codeception\Module
{
    /**
     * @var int Default delay to wait while page updating
     */
    protected int $defaultDelay = 5;

    /**
     * @param int $timeOut
     * @throws \Codeception\Exception\ModuleException
     */
    public function waitForPageUpdate(int $timeOut = 180): void
    {
        $I = $this->getModule('WebDriver');

        try {
            $I->waitForJS('return $.active == 0;', $timeOut);

            $I->debug('JS check passed: no active requests.');
        } catch (\Facebook\WebDriver\Exception\JavascriptErrorException $exception) {
            $I->wait($this->defaultDelay);

            $I->debug('JS Error: ' . $exception->getMessage());
        } catch (\Exception $e) {
            // Log any other exceptions
            $I->debug('Exception: ' . $e->getMessage());
            $I->wait($this->defaultDelay);
        }
    }
}
