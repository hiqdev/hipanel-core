<?php

namespace hipanel\tests\_support\Helper;

class ClickLinkHelper extends \Codeception\Module
{
    /**
     * @param $linkText
     * @throws \Codeception\Exception\ModuleException
     */
    public function clickLink(string $linkText, string $xpathPrefix = ''): void
    {
        $I = $this->getModule('WebDriver');
        $selector = $xpathPrefix . "//a[contains(text(), '$linkText')]";
        $I->click($selector);
    }
}
