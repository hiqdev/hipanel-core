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
     * @param int $timeOut
     * @throws \Codeception\Exception\ModuleException
     */
    public function waitForPageUpdate($timeOut = 60): void
    {
        $I = $this->getModule('WebDriver');

        $I->waitForJS('return $.active == 0;', $timeOut);
    }
}
