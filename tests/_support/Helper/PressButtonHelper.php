<?php

namespace hipanel\tests\_support\Helper;

class PressButtonHelper extends \Codeception\Module
{
    /**
     * @param $textOnButton
     * @throws \Codeception\Exception\ModuleException
     */
    public function pressButton(string $textOnButton, string $xpathPrefix = ''): void
    {
        $I = $this->getModule('WebDriver');

        $selector = $xpathPrefix . "//button[text() = '{$textOnButton}']";
        $I->click($selector);
    }
}
