<?php

namespace hipanel\tests\_support\Helper;

class pressButtonHelper extends \Codeception\Module
{
    /**
     * @param $textOnButton
     * @throws \Codeception\Exception\ModuleException
     */
    public function pressButton($textOnButton): void
    {
        $I = $this->getModule('WebDriver');

        $I->click("//button[text() = '{$textOnButton}']");
        $this->waitForPageUpdate();
    }

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
