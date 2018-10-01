<?php

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

        $I->waitForJS("return $.active == 0;", $timeOut);
    }
}
