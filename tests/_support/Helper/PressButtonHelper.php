<?php

namespace hipanel\tests\_support\Helper;

class PressButtonHelper extends \Codeception\Module
{
    /**
     * @param $textOnButton
     * @throws \Codeception\Exception\ModuleException
     */
    public function pressButton($textOnButton): void
    {
        $I = $this->getModule('WebDriver');

        $I->click("//button[text() = '{$textOnButton}']");
    }
}
